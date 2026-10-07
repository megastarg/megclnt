import json
with open("test_dump_curl.json", "r") as f:
    data = json.load(f)
    print("browseMetadata keys:", data.get("pageDataV4", {}).get("browseMetadata", {}).keys())
