package database

import (
	"fmt"
	"log"
	"os"
	"showme/models"

	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
)

var DB *gorm.DB

func Connect() {
	db, err := gorm.Open(postgres.Open(dsn()), &gorm.Config{
		Logger: logger.Default.LogMode(logger.Info),
	})

	if err != nil {
    panic("failed to connect database")
  }

	log.Println("connected")
	db.Logger = logger.Default.LogMode(logger.Info)
	log.Println("running migrations")
	migrate(db)

	DB = db
}

func migrate(db *gorm.DB) {
	db.AutoMigrate(&models.Request{})
}

func dsn() string {
	db_host := os.Getenv("DB_HOST")
	if db_host == "" {
		db_host = "localhost"
	}

	db_pass := os.Getenv("DB_PASS")
	if db_pass == "" {
		db_pass = "PostGres"
	}
	
	return fmt.Sprintf("host=%s database=postgres user=postgres password=%s", db_host, db_pass)
}