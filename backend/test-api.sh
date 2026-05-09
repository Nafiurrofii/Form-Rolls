#!/bin/bash
# API Testing with cURL

BASE_URL="http://localhost/Form-Roll/backend/roll.php"

echo "=== Form Roll API Testing ==="
echo ""

# ─── GET ALL ROLLS ───────────────────────────────
echo "1. GET All Rolls"
curl -X GET "$BASE_URL?action=get&page=1&limit=10"
echo -e "\n---\n"

# ─── GET ALL ROLLS WITH SEARCH ───────────────────
echo "2. GET All Rolls with Search"
curl -X GET "$BASE_URL?action=get&search=PUTIH&limit=5"
echo -e "\n---\n"

# ─── GET SINGLE ROLL ─────────────────────────────
echo "3. GET Single Roll (ID: 1)"
curl -X GET "$BASE_URL?action=get_one&id=1"
echo -e "\n---\n"

# ─── GET STATISTICS ──────────────────────────────
echo "4. GET Statistics"
curl -X GET "$BASE_URL?action=statistics"
echo -e "\n---\n"

# ─── CREATE NEW ROLL (POST) ──────────────────────
echo "5. CREATE New Roll (POST)"
curl -X POST "$BASE_URL?action=store" \
  -H "Content-Type: application/json" \
  -d '{
    "tanggal": "2026-05-07",
    "jam": "07:30:00",
    "roll": 100,
    "group_name": "A",
    "mesin": "20",
    "nama": "PP BIRU JUMBO",
    "denier": 1200,
    "panjang": 600,
    "lebar": 150,
    "anyam": "200",
    "berat": 95.5,
    "trace_code": "07-0730-A-20-100",
    "keterangan": "Test Create",
    "pic": "200"
  }'
echo -e "\n---\n"

# ─── UPDATE ROLL (POST) ──────────────────────────
echo "6. UPDATE Roll (POST) - Note: Replace ID with actual ID from create"
curl -X POST "$BASE_URL?action=update&id=1" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "PP PUTIH UPDATED",
    "berat": 99.9,
    "keterangan": "Updated via cURL"
  }'
echo -e "\n---\n"

# ─── DELETE SINGLE ROLL ──────────────────────────
echo "7. DELETE Single Roll (GET method)"
curl -X GET "$BASE_URL?action=delete&id=1"
echo -e "\n---\n"

# ─── DELETE WITH DELETE METHOD ───────────────────
echo "8. DELETE Roll (DELETE method)"
curl -X DELETE "$BASE_URL?action=delete&id=2"
echo -e "\n---\n"

# ─── DELETE MULTIPLE ROLLS ───────────────────────
echo "9. DELETE Multiple Rolls (POST)"
curl -X POST "$BASE_URL?action=delete_multiple" \
  -H "Content-Type: application/json" \
  -d '{
    "ids": [3, 4, 5]
  }'
echo -e "\n---\n"

echo "=== Testing Complete ==="
