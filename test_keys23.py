from curl_cffi import requests
import re
import json

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36"
}
r = requests.get(url, headers=headers, impersonate="chrome110")
html = r.text
print("Title:", re.search(r'<title>(.*?)</title>', html).group(1) if re.search(r'<title>(.*?)</title>', html) else "No title")

mat = re.search("__INITIAL_STATE__ = (.*?)};", html)
if mat:
    mat = mat[1] + "}"
    jsonarray = json.loads(mat)
    try:
        pages = jsonarray.get("pageDataV4", {}).get("page", {}).get("data", {})
        print("Keys in pageDataV4.page.data:", pages.keys())
    except Exception as e:
        print("Error getting pages", e)
