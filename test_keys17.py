from curl_cffi import requests

url = "https://1.rom.api.flipkart.com/api/4/page/fetch"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Origin": "https://www.flipkart.com",
    "Referer": "https://www.flipkart.com/",
    "X-User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop",
    "Content-Type": "application/json"
}
payload = {
    "pageUri": "/televisions/pr?sid=ckf,czl&sort=price_asc",
    "pageContext": {"fetchSeoData": True}
}

r = requests.post(url, headers=headers, json=payload, impersonate="chrome110")
print("Status:", r.status_code)
if r.status_code == 200:
    import json
    with open("api_response.json", "w") as f:
        json.dump(r.json(), f, indent=4)
    print("Success, saved to api_response.json")
else:
    print("Response:", r.text[:200])
