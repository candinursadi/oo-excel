### Upload Excel

| Service Name  | Endpoint |
| ------------- | ------------- |
| upload | {{url}}/api/upload  |

**Request (POST) :**
``` 
POST /api/upload HTTP/1.1
Host: localhost:8000
Content-Length: 246
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW

----WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="file"; filename="/D:/Downloads/Type_A.xlsx"
Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet

(data)
----WebKitFormBoundary7MA4YWxkTrZu0gW
```

**Response Success :**
``` 
{
    "message": "success"
}
```

**Response Failed :**
``` 
{
    "error": {
        "3": {
            "field_a": [
                "Missing value in field_a"
            ],
            "field_b": [
                "field_b should not contain any space"
            ],
            "field_d": [
                "Missing value in field_d"
            ]
        },
        "4": {
            "field_a": [
                "Missing value in field_a"
            ],
            "field_e": [
                "Missing value in field_e"
            ]
        }
    }
}
```
