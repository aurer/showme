package models

import (
	"crypto/md5"
	"encoding/json"
	"fmt"
	"sort"
)

type Param struct {
	Name  string
	Value []string
	Type  string
}

type Params struct {
	Params []Param
	CreatedAt string
}

// Hash creates an MD5 hash from a Param slice
func (p *Params) Hash() string {
	data := []byte(fmt.Sprintf("%v", p.Params))
	return fmt.Sprintf("%x", md5.Sum(data))
}

// ToJSON return a JSON string from a Param slice
func (p *Params) ToJSON() string {
	data, _ := json.Marshal(p)
	return string(data)
}

func (p *Params) Add(param Param) {
	p.Params = append(p.Params, param)
}

func (p *Params) Sort() {
	sort.Slice(p.Params, func(i, j int) bool { return p.Params[i].Name < p.Params[j].Name })	
}