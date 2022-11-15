package routes

import (
	"fmt"
	"html/template"
	"log"
	"net/url"
	"os"
	"showme/database"
	"showme/models"
	"showme/utils"

	"github.com/evanw/esbuild/pkg/api"
	"github.com/gofiber/fiber/v2"
	"gorm.io/datatypes"
	"gorm.io/gorm/clause"
)

func Root(c *fiber.Ctx) error {
	params := new(models.Params)
	
	queryString := c.Request().URI().QueryString()
	qs, _ := url.ParseQuery(string(queryString))
	for k, v := range qs {
		params.Add(models.Param{
			Name:  k,
			Value: v,
			Type:  "get",
		})
	}
	
	
	for key, values := range utils.PostParams(c.Request().PostArgs()) {
		params.Add(models.Param{
			Name:  string(key),
			Value: values,
			Type:  "post",
		})
	}
	

	if form, err := c.Request().MultipartForm(); err == nil {
		for k, v := range form.Value {
			params.Add(models.Param{
				Name:  k,
				Value: v,
				Type:  "file",
			})
		}
		for k, f := range form.File {
			params.Add(models.Param{
				Name:  k,
				Value: utils.GetFilenames(f),
				Type:  "file",
			})
		}
	}
	
	if len(params.Params) == 0 {
		FormJS, GetJS := getBookmarklets()
		return c.Render("index", fiber.Map{"FormJS": template.JSStr(FormJS), "GetJS": template.JSStr(GetJS)}, "layouts/plain")
	}

	params.Referer = string(c.Request().Header.Referer())
	params.SubmitPath = params.GetSubmitPath()
	
	params.Sort()
	RecordRequest(c, *params)
	
	return c.Render("params", params)
}

func RecordRequest(c *fiber.Ctx, p models.Params) {
	record := models.Request{
		ID: p.Hash(),
		Data: datatypes.JSON([]byte(p.ToJSON())),
		Method: c.Method(),
		UserAgent: c.Get("User-Agent"),
		AcceptLanguage: c.Get("Accept-Language"),
		Address: c.IP(),
	}

	database.DB.Clauses(clause.OnConflict{
		Columns: []clause.Column{{Name: "id"}},
		DoUpdates: clause.AssignmentColumns([]string{"updated_at"}),
	}).Create(&record)
}

func getBookmarklets() (string, string) {
	FormJS, err := os.ReadFile("assets/bookmarklets/ShowMeForm.js")
	if err != nil {
		log.Fatal(err)
	}
	FormJSMin := minifyJS(string(FormJS))
	FormJSMin = fmt.Sprintf("(function(){%s})()", FormJSMin)

	GetJS, err := os.ReadFile("assets/bookmarklets/ShowMeGet.js")
	if err != nil {
		log.Fatal(err)
	}
	GetJSMin := minifyJS(string(GetJS))
	GetJSMin = fmt.Sprintf("(function(){%s})()", GetJSMin)
	
	return FormJSMin, GetJSMin
}

func minifyJS(js string) string {
	result := api.Transform(js, api.TransformOptions{
		MinifyWhitespace:  true,
    MinifyIdentifiers: true,
    MinifySyntax:      true,
  })

	if result.Errors != nil {
		log.Println(result.Errors)
	}

	return string(result.Code)
}