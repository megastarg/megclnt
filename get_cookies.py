from playwright.sync_api import sync_playwright
import time

def get_flipkart_cookies():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        # It's better to use a standard user agent
        context = browser.new_context(user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36")
        page = context.new_page()
        
        print("Navigating to Flipkart...")
        page.goto("https://www.flipkart.com/televisions/pr?sid=ckf,czl", wait_until="networkidle")
        
        # Wait for potential bot checks to pass
        time.sleep(10)
        
        cookies = context.cookies()
        cookie_str = "; ".join([f"{c['name']}={c['value']}" for c in cookies])
        
        with open("cookie.txt", "w") as f:
            f.write("placeholder1\nplaceholder2\n" + cookie_str)
            
        print("Saved FULL cookies to cookie.txt")
        
        html = page.content()
        print("HTML Title:", html[html.find('<title>'):html.find('</title>')+8])
        browser.close()

if __name__ == "__main__":
    get_flipkart_cookies()
