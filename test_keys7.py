import json

with open("test_dump_curl.json", "r") as f:
    data = json.load(f)
    print(data.get("pageDataV4", {}).get("page", {}).get("data", {}).keys())
