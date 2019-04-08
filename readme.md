## Mô tả chức năng
1. Hiển thị các homestay
2. Tìm kiếm các homestay theo tên và khu vực. 
3. AutoSugguest khu vực và homestay.dùng fulltext search với tên khu vực. Ưu tiên kết quả chính xác lên trước.Frontend dùng debounce chống spam gõ quá nhanh.
4. Lọc theo khoảng giá tiền, Loại homestay. Reset bộ lọc
5. Sắp xếp theo giá tiền cao thấp, mới nhất, đánh giá
6. Phân trang, đếm số kết quả

## Note
Db test, danh sách các khu vực chỉ có trong Hà Nội. Thử search khu vực Hà Nội, Hồ Tây, Cầu giấy hoặc tên các homestay.
Module chỉ đưa ra các trường chính của homestay.

## Cài đặt

```bash
git clone https://github.com/chientinhte/homestays.git
cd homestays
composer install
cp .env.example .env
```
Thiết lập Database, import file [atb_test.sql](https://github.com/chientinhte/homestays/blob/master/atb_test.sql) đính kèm.
Cấu hình DB trong .env

```
php artisan key:generate
php artisan serve
```
Vào link [http://127.0.0.1:8000](http://127.0.0.1:8000) để xem.







