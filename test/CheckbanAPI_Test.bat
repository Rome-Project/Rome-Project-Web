@echo off
curl -X POST https://testing.rome-project.com/api/checkban.php ^
     -H "Content-Type: application/json" ^
     -H "Authorization: Bearer lol imagine puttin the token here lmao" ^
     -d "{\"player\": 85049433}"