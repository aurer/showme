package routes

import (
	"encoding/json"
	"log"
	"showme/database"
	"showme/models"

	"github.com/gofiber/fiber/v2"
)

func Saved(c *fiber.Ctx) error {
	var request models.Request
	r := database.DB.First(&request, "id = ?", c.Params("hash"))
	if r.RowsAffected < 1 {
		return c.Next()
	}

	p := new(models.Params)
	err := json.Unmarshal([]byte(request.Data), &p)
	if err != nil {
		log.Println(err)
	}
	p.CreatedAt = request.CreatedAt.Format("02.01.2006 15:04")

	return c.Render("params", p)
}