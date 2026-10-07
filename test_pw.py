from playwright.sync_api import sync_playwright

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page(user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36")
        
        def handle_request(request):
            if "api/4/page/fetch" in request.url:
                print("API Request URL:", request.url)
                print("API Request PostData:", request.post_data)
        
        def handle_response(response):
            if "api/4/page/fetch" in response.url:
                print("API Response URL:", response.url)
                try:
                    data = response.json()
                    slots = data.get("RESPONSE", {}).get("slots", [])
                    print("Number of slots:", len(slots))
                    for i, s in enumerate(slots):
                        wtype = s.get("widget", {}).get("type")
                        print(f"Slot {i} type:", wtype)
                except:
                    pass

        page.on("request", handle_request)
        page.on("response", handle_response)
        
        page.goto("https://www.flipkart.com/televisions/pr?sid=ckf,czl&sort=price_asc", wait_until="networkidle")
        
        html = page.content()
        print("HTML Title:", html[html.find('<title>'):html.find('</title>')+8])
        browser.close()

run()
