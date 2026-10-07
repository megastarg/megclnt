from curl_cffi import requests

url = "https://www.flipkart.com/api/4/page/fetch"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Origin": "https://www.flipkart.com",
    "Referer": "https://www.flipkart.com/",
    "X-User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 FKUA/website/42/website/Desktop",
    "Content-Type": "application/json"
}
payload = {
    "pageUri": "/televisions/pr?sid=ckf,czl&sort=price_asc"
}

r = requests.post(url, headers=headers, json=payload, impersonate="chrome110")
import json
try:
    data = r.json()
    slots = data.get("RESPONSE", {}).get("slots", [])
    print("Number of slots:", len(slots))
    for s in slots:
        if s.get("widget", {}).get("type") == "PRODUCT_SUMMARY":
            print("Found PRODUCT_SUMMARY!")
            break
        elif "products" in json.dumps(s):
            print("Found 'products' in slot type:", s.get("widget", {}).get("type"))
except Exception as e:
    print(e)
