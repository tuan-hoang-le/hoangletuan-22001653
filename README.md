# Thực hành: Phát triển ứng dụng web (học kỳ 1, năm học 2026 - 2027)

Cấu trúc cây thư mục và tệp tin cho các buổi học thực hành của môn học được tổ chức như sau:

```text
root
├── lab_1_thu_17_09_2026/
│   ├── oop_students.php          # Giải quyết bài 4 (hướng đối tượng) của buổi 1 với các class Student và StudentManager.
│   └── procedural_students.php   # Giải quyết bài 1, 2, 3 (hướng thủ tục) của buổi 1 với các hàm xử lý mảng và vòng lặp.
├── lab_2_thu_24_09_2026/
│   ├── movie_management.php      # Giải quyết bài 2 của buổi 2: Quản lý vé xem phim kết hợp OOP và hàm xử lý mảng.
│   └── shopping_cart.php         # Giải quyết bài 1 của buổi 2: Quản lý giỏ hàng mua sắm sử dụng OOP.
├── lab_3_thu_01_10_2026/
│   ├── movie_management.sql      # Giải quyết bài 2 của buổi 3: Các câu lệnh truy vấn SQL quản lý vé xem phim.
│   └── shopping_cart.sql         # Giải quyết bài 1 của buổi 3: Các câu lệnh truy vấn SQL quản lý giỏ hàng.
├── lab_4_thu_08_10_2026/
│   ├── app/
│   │   ├── common/
│   │   │   ├── config.php        # Định nghĩa các hằng số cấu hình kết nối cơ sở dữ liệu và tên bảng.
│   │   │   └── dbConnect.php     # Khởi tạo kết nối PHP với MySQL (MySQLi) và xử lý lỗi kết nối.
│   │   ├── model/
│   │   │   └── product.php       # Tầng model: Hiện thực các hàm xử lý dữ liệu (CRUD) cho sản phẩm.
│   │   ├── view/
│   │   │   ├── footer.php        # Giao diện chân trang (footer).
│   │   │   └── header.php        # Giao diện đầu trang (header) gồm menu điều hướng và CSS cơ bản.
│   │   ├── index.php             # Điều hướng người dùng tự động sang trang danh sách sản phẩm.
│   │   ├── product_add.php       # Giao diện form và xử lý thêm sản phẩm mới (có kiểm tra dữ liệu đầu vào).
│   │   ├── product_delete.php    # Giao diện xác nhận và xử lý xóa sản phẩm theo ID.
│   │   ├── product_edit.php      # Giao diện form và xử lý cập nhật thông tin sản phẩm (có kiểm tra dữ liệu đầu vào).
│   │   └── product_list.php      # Giao diện hiển thị bảng danh sách sản phẩm cùng các chức năng sửa, xóa.
│   └── shopping_cart.sql         # Kịch bản SQL tạo cơ sở dữ liệu, bảng products và chèn các bản ghi mẫu.
├── lab_5_thu_15_10_2026/
│   ├── app/
│   │   ├── common/
│   │   │   ├── config.php        # Định nghĩa các hằng số cấu hình kết nối cơ sở dữ liệu và tên các bảng (products, users).
│   │   │   └── dbConnect.php     # Khởi tạo kết nối PHP với MySQL (MySQLi) và xử lý lỗi kết nối an toàn.
│   │   ├── model/
│   │   │   ├── product.php       # Tầng model: Hiện thực các hàm xử lý dữ liệu (CRUD) cho sản phẩm và kiểm tra dữ liệu đầu vào.
│   │   │   └── user.php          # Tầng model: Hiện thực các hàm xử lý dữ liệu người dùng, đăng ký, đăng nhập và kiểm soát truy cập.
│   │   ├── view/
│   │   │   ├── footer.php        # Giao diện chân trang (footer).
│   │   │   └── header.php        # Giao diện đầu trang (header) hiển thị menu điều hướng, thông tin tài khoản đăng nhập và nút đăng xuất.
│   │   ├── index.php             # Điều hướng người dùng tự động: chuyển đến danh sách sản phẩm nếu đã đăng nhập, ngược lại chuyển đến trang đăng nhập.
│   │   ├── login.php             # Giao diện và xử lý đăng nhập, xác thực mật khẩu bằng password_verify() và khởi tạo phiên làm việc (session).
│   │   ├── logout.php            # Xử lý đăng xuất, xóa toàn bộ phiên làm việc (session) và cookie, chuyển hướng về trang đăng nhập.
│   │   ├── product_add.php       # Giao diện form và xử lý thêm sản phẩm mới (chỉ cho phép người dùng đã đăng nhập).
│   │   ├── product_delete.php    # Giao diện xác nhận và xử lý xóa sản phẩm theo ID (chỉ cho phép người dùng đã đăng nhập).
│   │   ├── product_edit.php      # Giao diện form và xử lý cập nhật thông tin sản phẩm (chỉ cho phép người dùng đã đăng nhập).
│   │   ├── product_list.php      # Giao diện bảng danh sách sản phẩm cùng các chức năng sửa, xóa (chỉ cho phép người dùng đã đăng nhập).
│   │   └── register.php          # Giao diện form và xử lý đăng ký tài khoản mới, mã hóa mật khẩu bằng password_hash() trước khi lưu vào CSDL.
│   └── shopping_cart.sql         # Kịch bản SQL tạo cơ sở dữ liệu, các bảng products, users và chèn các bản ghi mẫu.
└── midterm/
    ├── problem_1_student_grading.php # Giải quyết bài 1 trong đề thi giữa kỳ: Xếp loại học lực học sinh theo thang điểm bằng class Student và câu lệnh điều kiện.
    └── problem_2_product_pricing.php # Giải quyết bài 2 trong đề thi giữa kỳ: Quản lý mảng giá sản phẩm, tính tổng giá trị và tìm giá cao nhất bằng class ProductPriceManager.
```