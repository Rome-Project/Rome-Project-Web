@echo off
curl -X POST https://testing.rome-project.com/api/ban.php ^
     -H "Content-Type: application/json" ^
     -H "Authorization: Bearer lol imagine puttin the token here lmao" ^
     -d "{\"player\": 85049433, \"mod\": 235422729, \"duration\": 3600, \"reason\": \"Being British\"}"