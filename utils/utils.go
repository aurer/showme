package utils

import (
	"mime/multipart"

	"github.com/valyala/fasthttp"
)

// GetFilenames returns a string slice containing the Filename of each FileHeader
func GetFilenames(files []*multipart.FileHeader) []string {
	var names []string
	for _, f := range files {
		names = append(names, f.Filename)
	}
	return names
}

// PostParam creates a map[string][]string from a fasthttp Args struct which may contain duplicate keys
func PostParams(args *fasthttp.Args) map[string][]string {
	params := make(map[string][]string)
	
	args.VisitAll(func (k, v []byte) {
		key := string(k)
		value := string(v)
		if params[key] != nil {
			params[key] = append(params[key], value)
		} else {
			params[key] = []string{value}
		}
	})
	
	return params
}