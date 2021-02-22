package main

import (
	"log"
	"net/http"
	"html/template"
	"math/rand"
	"time"
	"strconv"
	"fmt"
	"strings"
)
var randomNumber []int
var history 	 string

type IndexData struct {
	Title   string
	Content string
	Poi 	string
	Reply	string
}

func random_num() []int {
    initial := []int{0, 1, 2, 3, 4, 5, 6, 7, 8, 9} 
    random := make([]int, 4)    
    r := rand.New(rand.NewSource(time.Now().UnixNano()))

    for i := 0; i < 4; i++ {
	    tmp := r.Intn(len(initial) - i) 

	    random[i] = initial[tmp] 
        initial[tmp], initial[len(initial)-1-i] = initial[len(initial)-1-i], initial[tmp]
	}
	
	if random[0] == 0 {
		random = random_num()
	}
    return random
}

func test(w http.ResponseWriter, r *http.Request) {
	tmpl := template.Must(template.ParseFiles("./index.html"))
	data := new(IndexData)
	data.Title = "請輸入4位數字"
	tmpl.Execute(w, data)
}

func test2(w http.ResponseWriter, r *http.Request) {
	A := 0
	B := 0
	AA := "A"
	BB := "B"
	var userarrS []string
	var userarrI [4]int

	r.ParseForm()

	if r.Method == "POST" {
		user := r.FormValue("number")
		userarrS = strings.Split(user, "")
		//驗證數字是否為4位數
		if len(userarrS) != 4 {
			data := new(IndexData)
			data.Poi = "數字錯誤"
			w.Write([]byte(data.Poi))
			return
		}

		for i := 0; i < 4; i++ {
			if i+1 != 4 && userarrS[i] == userarrS[i+1] {
				data := new(IndexData)
				data.Poi = "不能輸入重複數字"
				w.Write([]byte(data.Poi))
				return
			}
		}

		for i := 0; i < 4; i++ {
			userarrI[i], _ = strconv.Atoi(userarrS[i])
		}

		for i := 0; i < 4; i++ {
			if userarrI[i] == randomNumber[i] {
				A++
			}
			for j := 0; j < len(userarrI); j++ {
				if userarrI[i] == randomNumber[j] {
					B++
				}
			}
		}
		//因為是每次都計算B 所以要扣掉正確的數量
		B -= A

		history = fmt.Sprintf("%v<br>%v%v%v%v %v%v%v%v",history, userarrI[0], userarrI[1], userarrI[2], userarrI[3], A, AA ,B, BB, )
		Reply := fmt.Sprintf("%v%v%v%v<br> %v", A, AA ,B, BB, history)
		w.Write([]byte(Reply))
		
		if A == 4 {
			randomNumber = random_num()
			history = ""
		}
		return
	} else {
		tmpl := template.Must(template.ParseFiles("./index.html"))
		data := new(IndexData)
		data.Title = "請輸入4位數字"
		tmpl.Execute(w, data)
	}
}
func answer(w http.ResponseWriter, r *http.Request)  {
	r.ParseForm()
	Reply := fmt.Sprintf("答案為%v%v%v%v", randomNumber[0], randomNumber[1], randomNumber[2], randomNumber[3])
	if r.Method == "POST" {
		w.Write([]byte(Reply))
		randomNumber = random_num()
		return
	} else {
		tmpl := template.Must(template.ParseFiles("./index.html"))
		data := new(IndexData)
		data.Title = "請輸入4位數字"
		tmpl.Execute(w, data)
	}
}
func main() {
	randomNumber = random_num()
	
	http.HandleFunc("/", test)
	http.HandleFunc("/game", test2)
	http.HandleFunc("/answer", answer)


	err := http.ListenAndServe(":8000", nil)
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}
}