package main

import (
	"fmt"
	"log"
	"os"

	"showme/database"
	"showme/routes"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/template/html"
)

func main() {
	database.Connect()

	app := fiber.New(fiber.Config{
		Views: html.New("./views", ".html"),
		ViewsLayout: "layouts/base",
	})

	app.Static("/assets", "./assets")
	app.All("/", routes.Root)
	app.Get("/:hash<len(32)>", routes.Saved)
	app.Use(routes.NotFound)

	port := os.Getenv("PORT")
	if port == "" {
		port = "3000"
	}

	port = fmt.Sprintf(":%s", port)
	log.Fatal(app.Listen(port))
}
