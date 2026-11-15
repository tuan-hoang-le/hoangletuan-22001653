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
├── lab_6_thu_29_10_2026/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── movie_ticket_booking.css  # Định dạng giao diện cho bài 2: Bố cục rạp phim tối, tương phản cao và hiệu ứng tương tác.
│   │   │   ├── personal_task_manager.css # Định dạng giao diện cho bài 3: Bố cục danh sách công việc tối giản, trực quan, hỗ trợ gạch ngang và bộ đếm.
│   │   │   └── student_info_card.css     # Định dạng giao diện cho bài 1: Thẻ thông tin sinh viên, huy hiệu xếp loại theo điểm và bảng điều khiển thử nghiệm.
│   │   └── js/
│   │       ├── movie_ticket_booking.js   # Xử lý logic Vue.js cho bài 2: Tính tổng tiền, chọn loại vé, suất chiếu, combo và xuất vé điện tử.
│   │       ├── personal_task_manager.js  # Xử lý logic Vue.js cho bài 3: Thêm, xóa, đánh dấu hoàn thành, lọc theo trạng thái và thống kê số lượng công việc.
│   │       ├── student_info_card.js      # Xử lý logic Vue.js cho bài 1: Xếp loại học lực theo thang điểm, đổi màu huy hiệu và bật/tắt phần giới thiệu.
│   │       └── vue.global.js             # Thư viện Vue.js 3 bản độc lập (global build) phục vụ chạy ngoại tuyến (offline).
│   ├── movie_ticket_booking.html         # Giao diện bài 2: Ứng dụng đặt vé xem phim với Vue.js (v-model, modifiers, computed, @submit.prevent).
│   ├── personal_task_manager.html        # Giao diện bài 3: Ứng dụng quản lý công việc cá nhân với Vue.js (v-for, :key, checkbox, bộ lọc trạng thái).
│   └── student_info_card.html            # Giao diện bài 1: Thẻ thông tin sinh viên với Vue.js (createApp, data, v-text, v-bind, v-if, v-show).
├── lab_7_thu_05_11_2026/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── product_catalog_component.css # Định dạng giao diện cho bài 2: Lưới thẻ sản phẩm hiện đại, hiệu ứng tương tác và hộp mô tả mở rộng.
│   │   │   ├── product_list_statistics.css   # Định dạng giao diện cho bài 1: Bảng điều khiển quản trị kho, khối thống kê metric và bảng dữ liệu.
│   │   │   └── shopping_cart_emit.css        # Định dạng giao diện cho bài 3: Bảng giỏ hàng, cụm nút tăng giảm số lượng và thẻ tổng tiền thanh toán.
│   │   └── js/
│   │       ├── product_catalog_component.js  # Xử lý logic Vue.js cho bài 2: Quản lý danh mục sản phẩm, đăng ký component ProductCard và sự kiện mua hàng.
│   │       ├── product_list_statistics.js    # Xử lý logic Vue.js cho bài 1: Tìm kiếm theo tên, lọc theo danh mục và tính toán thống kê (computed) tổng tồn kho, giá trị hàng hóa.
│   │       ├── shopping_cart_emit.js         # Xử lý logic Vue.js cho bài 3: Quản lý giỏ hàng, lắng nghe sự kiện phát ra ($emit) từ component con và tính tổng tiền.
│   │       └── vue.global.js                 # Thư viện Vue.js 3 bản độc lập (global build) phục vụ chạy ngoại tuyến (offline).
│   ├── components/
│   │   ├── CartItem.js                       # Component hiển thị từng dòng sản phẩm trong giỏ hàng, nhận props và phát sự kiện (emits) thay đổi số lượng, xóa mục.
│   │   └── ProductCard.js                    # Component thẻ sản phẩm độc lập, nhận props xác thực (name, price, stock,...) và quản lý trạng thái ẩn/hiện mô tả cục bộ.
│   ├── product_catalog_component.html        # Giao diện bài 2: Danh mục sản phẩm sử dụng kiến trúc Component độc lập với props và dữ liệu cục bộ.
│   ├── product_list_statistics.html          # Giao diện bài 1: Danh sách sản phẩm kết hợp tìm kiếm, bộ lọc danh mục và thống kê số liệu thời gian thực.
│   └── shopping_cart_emit.html               # Giao diện bài 3: Giỏ hàng trực quan hiện thực luồng dữ liệu một chiều và giao tiếp component bằng $emit.
├── lab_8_thu_12_11_2026/
│   ├── api/
│   │   └── products.php                      # RESTful API cung cấp các phương thức GET, POST, PUT, DELETE, kiểm tra dữ liệu đầu vào và dùng PDO prepared statement.
│   ├── config/
│   │   └── Database.php                      # Lớp quản lý kết nối cơ sở dữ liệu MySQL bằng PDO theo mô hình singleton, cấu hình UTF-8 và bắt lỗi PDOException.
│   ├── css/
│   │   └── style.css                         # Định dạng giao diện ứng dụng quản lý sản phẩm: Bảng dữ liệu, biểu mẫu thêm/sửa, nút thao tác và hộp thông báo.
│   ├── js/
│   │   ├── app.js                            # Xử lý logic Vue.js 3: Gọi API backend (fetch), liên kết biểu mẫu (v-model), xác nhận xóa và tự động cập nhật bảng không tải lại trang.
│   │   └── vue.global.js                     # Thư viện Vue.js 3 bản độc lập (global build) phục vụ chạy ngoại tuyến (offline).
│   ├── index.html                            # Giao diện ứng dụng Quản lý sản phẩm tích hợp Vue.js, hiển thị bảng sản phẩm (v-for) và biểu mẫu thêm/sửa linh hoạt.
│   └── product_management.sql                # Kịch bản SQL tạo cơ sở dữ liệu product_management, bảng products (ràng buộc CHECK) và chèn dữ liệu mẫu ban đầu.
└── midterm/
    ├── problem_1_student_grading.php         # Giải quyết bài 1 trong đề thi giữa kỳ: Xếp loại học lực học sinh theo thang điểm bằng class Student và câu lệnh điều kiện.
    └── problem_2_product_pricing.php         # Giải quyết bài 2 trong đề thi giữa kỳ: Quản lý mảng giá sản phẩm, tính tổng giá trị và tìm giá cao nhất bằng class ProductPriceManager.
```