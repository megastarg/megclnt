from playwright.sync_api import sync_playwright

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        page.goto("https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc", wait_until="networkidle")
        html = page.content()
        with open("playwright.html", "w") as f:
            f.write(html)
        print("Length:", len(html))
        print("Samsung count:", html.count("Samsung"))
        browser.close()

run()
