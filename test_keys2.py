import json

with open("test_dump.json", "r") as f:
    data = json.load(f)
    print("pageDataV4 keys:", data.get("pageDataV4", {}).keys())
    if "page" in data.get("pageDataV4", {}):
        print("page keys:", data["pageDataV4"]["page"].keys())
        if "data" in data["pageDataV4"]["page"]:
            print("data keys:", data["pageDataV4"]["page"]["data"].keys())
            
