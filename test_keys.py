import re
import json
import requests

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36"
}
try:
    r = requests.get(url, headers=headers)
    html = r.text
    mat = re.search("__INITIAL_STATE__ = (.*?)};", html)
    if mat:
        mat = mat[1] + "}"
        jsonarray = json.loads(mat)
        with open("test_dump.json", "w") as f:
            json.dump(jsonarray, f, indent=4)
except Exception as e:
    print(e)
