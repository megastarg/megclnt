import re
import json
from curl_cffi import requests

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Language": "en-GB,en;q=0.9",
    "Referer": "https://www.flipkart.com/",
}

with open("cookie.txt", "r") as f:
    lines = f.readlines()
    if len(lines) >= 3:
        headers["Cookie"] = lines[2].strip()

r = requests.get(url, headers=headers, impersonate="chrome110")
html = r.text
print("Status Code:", r.status_code)
title_match = re.search(r'<title>(.*?)</title>', html)
print("Title:", title_match.group(1) if title_match else "No title")

mat = re.search("__INITIAL_STATE__ = (.*?)};", html)
if mat:
    mat = mat[1] + "}"
    jsonarray = json.loads(mat)
    try:
        pages = jsonarray.get("pageDataV4", {}).get("page", {}).get("data", {})
        if "10003" in pages:
            print("FOUND 10003! Products:", len(pages["10003"]))
        else:
            print("10003 not found. Keys:", pages.keys())
    except Exception as e:
        print("Error getting pages", e)
