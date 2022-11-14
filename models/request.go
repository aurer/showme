package models

import (
	"time"

	"gorm.io/datatypes"
)

type Request struct {
	ID 							string `gorm:"primaryKey"`
	CreatedAt 			time.Time
	UpdatedAt 			time.Time
	Data 						datatypes.JSON
	Method 					string
	UserAgent 			string
	AcceptLanguage 	string
	Address 				string
}
