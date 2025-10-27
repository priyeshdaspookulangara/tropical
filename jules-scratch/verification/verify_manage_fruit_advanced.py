
from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()
    # This will likely redirect to the login page, which is fine for verification
    page.goto("http://localhost:8000/admin/manage_fruit_advanced.php")
    page.screenshot(path="jules-scratch/verification/manage_fruit_advanced.png")
    browser.close()

with sync_playwright() as playwright:
    run(playwright)
