from curl_cffi import requests
url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36"
}
r = requests.get(url, headers=headers, impersonate="chrome110")
with open("test.html", "w") as f:
    f.write(r.text)
