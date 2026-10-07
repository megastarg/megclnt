from curl_cffi import requests
url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Language": "en-US,en;q=0.9"
}
r = requests.get(url, headers=headers, impersonate="chrome110", allow_redirects=False)
print(r.status_code)
print(r.headers.get("Location"))
