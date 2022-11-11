package main

import (
	"crypto/md5"
	"fmt"
	"html/template"
	"log"
	"mime/multipart"
	"net/http"
	"net/url"
	"sort"
	"strings"
)

func main() {
	fs := http.FileServer(http.Dir("assets/"))
	http.Handle("/assets/", http.StripPrefix("/assets/", fs))
	http.HandleFunc("/examples", getExamples)
	http.HandleFunc("/", getRoot)
	log.Fatal(http.ListenAndServe(":3333", nil))
}

func Qs(uri string) string {
	if i := strings.Index(uri, "?"); i > -1 {
		return uri[i+1:]
	}

	return ""
}

type RequestParams struct {
	Params []Param
}

func (r RequestParams) Hash() string {
	data := []byte(fmt.Sprintf("%v", r))
	return fmt.Sprintf("%x", md5.Sum(data))
}

type Param struct {
	Name  string
	Value []string
	Type  string
}

func getRoot(w http.ResponseWriter, r *http.Request) {
	qs, err := url.ParseQuery(Qs(r.RequestURI))
	if err != nil {
		log.Fatal(err)
	}

	var params []Param = []Param{}
	for k, v := range qs {
		params = append(params, Param{
			Name:  k,
			Value: v,
			Type:  "get",
		})
	}

	r.ParseForm()

	post := r.PostForm
	for k, v := range post {
		params = append(params, Param{
			Name:  k,
			Value: v,
			Type:  "post",
		})
	}

	if err := r.ParseMultipartForm(1024); err == nil {
		files := r.MultipartForm.File
		for k, v := range files {
			params = append(params, Param{
				Name:  k,
				Value: GetFilenames(v),
				Type:  "file",
			})
		}
	}

	t, err := template.ParseFiles("views/base.html", "views/index.html")
	if err != nil {
		log.Fatal(err)
	}

	sort.Slice(params, func(i, j int) bool { return params[i].Name < params[j].Name })
	requestParams := RequestParams{Params: params}

	err = t.ExecuteTemplate(w, "base", requestParams)
	if err != nil {
		log.Fatal(err)
	}
}

func getExamples(w http.ResponseWriter, r *http.Request) {
	t, err := template.ParseFiles("views/base.html", "views/examples.html")
	if err != nil {
		log.Fatal(err)
	}

	t.ExecuteTemplate(w, "base", nil)
}

func GetFilenames(files []*multipart.FileHeader) []string {
	var names []string
	for _, f := range files {
		names = append(names, f.Filename)
	}
	return names
}
