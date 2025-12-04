-- Seed Data for Used Car Marketplace
-- Run this after migrations

-- Insert Brands
INSERT INTO `brands` (`id`, `name`, `slug`, `status`) VALUES
(1, 'Toyota', 'toyota', 'active'),
(2, 'Honda', 'honda', 'active'),
(3, 'Mazda', 'mazda', 'active'),
(4, 'Isuzu', 'isuzu', 'active'),
(5, 'Mitsubishi', 'mitsubishi', 'active'),
(6, 'Nissan', 'nissan', 'active'),
(7, 'Ford', 'ford', 'active'),
(8, 'Chevrolet', 'chevrolet', 'active'),
(9, 'Mercedes-Benz', 'mercedes-benz', 'active'),
(10, 'BMW', 'bmw', 'active'),
(11, 'Audi', 'audi', 'active'),
(12, 'Volkswagen', 'volkswagen', 'active'),
(13, 'Suzuki', 'suzuki', 'active'),
(14, 'MG', 'mg', 'active'),
(15, 'Hyundai', 'hyundai', 'active');

-- Insert Models
INSERT INTO `models` (`brand_id`, `name`, `slug`, `status`) VALUES
-- Toyota
(1, 'Camry', 'camry', 'active'),
(1, 'Corolla Altis', 'corolla-altis', 'active'),
(1, 'Yaris', 'yaris', 'active'),
(1, 'Vios', 'vios', 'active'),
(1, 'Fortuner', 'fortuner', 'active'),
(1, 'Hilux Revo', 'hilux-revo', 'active'),
(1, 'C-HR', 'c-hr', 'active'),
(1, 'Alphard', 'alphard', 'active'),
-- Honda
(2, 'Civic', 'civic', 'active'),
(2, 'Accord', 'accord', 'active'),
(2, 'City', 'city', 'active'),
(2, 'Jazz', 'jazz', 'active'),
(2, 'CR-V', 'cr-v', 'active'),
(2, 'HR-V', 'hr-v', 'active'),
(2, 'BR-V', 'br-v', 'active'),
-- Mazda
(3, 'Mazda2', 'mazda2', 'active'),
(3, 'Mazda3', 'mazda3', 'active'),
(3, 'CX-3', 'cx-3', 'active'),
(3, 'CX-5', 'cx-5', 'active'),
(3, 'CX-30', 'cx-30', 'active'),
(3, 'BT-50', 'bt-50', 'active'),
-- Isuzu
(4, 'D-Max', 'd-max', 'active'),
(4, 'MU-X', 'mu-x', 'active'),
-- Mitsubishi
(5, 'Pajero Sport', 'pajero-sport', 'active'),
(5, 'Triton', 'triton', 'active'),
(5, 'Xpander', 'xpander', 'active'),
(5, 'Attrage', 'attrage', 'active'),
-- Nissan
(6, 'Almera', 'almera', 'active'),
(6, 'Kicks', 'kicks', 'active'),
(6, 'X-Trail', 'x-trail', 'active'),
(6, 'Navara', 'navara', 'active'),
-- Ford
(7, 'Ranger', 'ranger', 'active'),
(7, 'Everest', 'everest', 'active'),
-- Mercedes-Benz
(9, 'C-Class', 'c-class', 'active'),
(9, 'E-Class', 'e-class', 'active'),
(9, 'GLA', 'gla', 'active'),
-- BMW
(10, '3 Series', '3-series', 'active'),
(10, '5 Series', '5-series', 'active'),
(10, 'X1', 'x1', 'active'),
(10, 'X3', 'x3', 'active');

-- Insert Users (password: password123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `province`, `role`, `status`) VALUES
(1, 'Admin System', 'admin@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0812345678', 'กรุงเทพมหานคร', 'admin', 'active'),
(2, 'สมชาย ขายรถ', 'seller1@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0891234567', 'กรุงเทพมหานคร', 'seller', 'active'),
(3, 'วิภา มอเตอร์', 'seller2@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0892345678', 'เชียงใหม่', 'seller', 'active'),
(4, 'ศักดิ์ชัย ออโต้', 'seller3@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0893456789', 'ชลบุรี', 'seller', 'active'),
(5, 'ประยุทธ์ คาร์', 'seller4@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0894567890', 'นครราชสีมา', 'seller', 'active'),
(6, 'นิดา มอเตอร์เซลส์', 'seller5@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0895678901', 'ขอนแก่น', 'seller', 'active'),
(7, 'มานะ หาซื้อ', 'buyer1@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0896789012', 'กรุงเทพมหานคร', 'buyer', 'active'),
(8, 'สมศรี ใจดี', 'buyer2@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0897890123', 'ภูเก็ต', 'buyer', 'active'),
(9, 'วรรณา มองหา', 'buyer3@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0898901234', 'นนทบุรี', 'buyer', 'active'),
(10, 'สุรชัย อยากได้', 'buyer4@usedcar.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0899012345', 'สมุทรปราการ', 'buyer', 'active');

-- Insert Cars (30 cars)
INSERT INTO `cars` (`user_id`, `brand_id`, `model_id`, `title`, `slug`, `description`, `price`, `year`, `mileage`, `color`, `engine_size`, `transmission`, `fuel_type`, `province`, `status`, `is_featured`, `views`) VALUES
(2, 1, 1, 'Toyota Camry 2.5 HV Premium ปี 2022 สภาพป้ายแดง', 'toyota-camry-25-hv-premium-2022', 'รถสภาพดีมาก ใช้งานน้อย เข้าศูนย์ตลอด มีประวัติการเข้าศูนย์ครบ รถบ้านมือเดียว ไม่เคยชน ไม่เคยจมน้ำ พร้อมโอน', 1450000, 2022, 25000, 'ขาวมุก', '2500', 'auto', 'hybrid', 'กรุงเทพมหานคร', 'published', 1, 156),
(2, 1, 5, 'Toyota Fortuner 2.8 V 4WD ปี 2021 ท็อปสุด', 'toyota-fortuner-28-v-4wd-2021', 'Fortuner รุ่นท็อป ขับ 4 เครื่องดีเซล ประหยัดน้ำมัน ภายในสะอาด ไม่มีตำหนิ รถใช้งานครอบครัว ดูแลอย่างดี', 1350000, 2021, 45000, 'ดำ', '2800', 'auto', 'diesel', 'กรุงเทพมหานคร', 'published', 1, 234),
(2, 2, 9, 'Honda Civic 1.5 Turbo RS ปี 2023 วิ่งน้อย', 'honda-civic-15-turbo-rs-2023', 'Civic รุ่นใหม่ล่าสุด เครื่อง Turbo แรง ประหยัด รถใหม่มาก ยังอยู่ในประกัน ของแต่งครบ ราคาต่อรองได้', 1050000, 2023, 12000, 'แดง', '1500', 'auto', 'gasoline', 'กรุงเทพมหานคร', 'published', 1, 189),
(3, 3, 19, 'Mazda CX-5 2.0 SP ปี 2022 สีแดงสวย', 'mazda-cx-5-20-sp-2022', 'CX-5 รุ่น SP สีแดง Soul Red ออฟชั่นเต็ม Sunroof ภายในหนังแท้ รถสวยมาก ไม่มีชน เข้าศูนย์ตลอด', 1150000, 2022, 32000, 'แดง Soul Red', '2000', 'auto', 'gasoline', 'เชียงใหม่', 'published', 1, 145),
(3, 1, 6, 'Toyota Hilux Revo 2.8 G 4WD ปี 2021', 'toyota-hilux-revo-28-g-4wd-2021', 'Revo Double Cab ขับ 4 เครื่องแรง ทนทาน ใช้งานหนักได้ สภาพดี พร้อมใช้งาน ราคาคุ้มค่า', 850000, 2021, 65000, 'เทา', '2800', 'auto', 'diesel', 'เชียงใหม่', 'published', 0, 98),
(3, 2, 13, 'Honda CR-V 2.4 EL 4WD ปี 2020 ฟูลออฟชั่น', 'honda-cr-v-24-el-4wd-2020', 'CR-V รุ่นท็อป ขับ 4 ออฟชั่นครบ เบาะหนังไฟฟ้า Sunroof กล้องรอบคัน รถครอบครัว ใช้งานน้อย', 1050000, 2020, 48000, 'ขาว', '2400', 'auto', 'gasoline', 'เชียงใหม่', 'published', 0, 87),
(4, 4, 22, 'Isuzu D-Max 1.9 Blue Power ปี 2022', 'isuzu-d-max-19-blue-power-2022', 'D-Max รุ่นใหม่ เครื่อง 1.9 ประหยัดน้ำมันมาก รถใช้งานเบา ไม่บรรทุกหนัก สภาพเหมือนใหม่', 680000, 2022, 28000, 'น้ำเงิน', '1900', 'manual', 'diesel', 'ชลบุรี', 'published', 0, 76),
(4, 5, 24, 'Mitsubishi Pajero Sport 2.4 GT Premium ปี 2021', 'mitsubishi-pajero-sport-24-gt-premium-2021', 'Pajero Sport ท็อปสุด ออฟชั่นครบ ขับ 4 ได้ รถแกร่ง ทนทาน เหมาะใช้งานครอบครัว หรือเดินทางไกล', 1180000, 2021, 52000, 'ขาว', '2400', 'auto', 'diesel', 'ชลบุรี', 'published', 1, 134),
(4, 6, 30, 'Nissan Navara 2.3 V 4WD ปี 2020', 'nissan-navara-23-v-4wd-2020', 'Navara Double Cab ขับ 4 เครื่องแรง ช่วงล่างนุ่ม สบาย รถบ้านมือเดียว ดูแลดี', 720000, 2020, 58000, 'ดำ', '2300', 'auto', 'diesel', 'ชลบุรี', 'published', 0, 65),
(5, 1, 4, 'Toyota Vios 1.5 G ปี 2022 สภาพนางฟ้า', 'toyota-vios-15-g-2022', 'Vios รุ่น G ออฟชั่นครบ ประหยัดน้ำมัน รถมือเดียว ไม่เคยชน ไม่เคยติดแก๊ส เข้าศูนย์ตลอด', 550000, 2022, 22000, 'บรอนซ์เงิน', '1500', 'auto', 'gasoline', 'นครราชสีมา', 'published', 0, 88),
(5, 2, 11, 'Honda City 1.0 Turbo SV ปี 2021', 'honda-city-10-turbo-sv-2021', 'City เครื่อง Turbo ประหยัด แรง รูปทรงสวย ภายในกว้าง รถใช้งานน้อย สภาพดีมาก', 580000, 2021, 35000, 'ขาว', '1000', 'auto', 'gasoline', 'นครราชสีมา', 'published', 0, 72),
(5, 3, 17, 'Mazda3 2.0 SP ปี 2020 รถสวยมาก', 'mazda3-20-sp-2020', 'Mazda3 รุ่นใหม่ ดีไซน์สวย ออฟชั่นเต็ม ขับสนุก ประหยัดน้ำมัน รถบ้าน ดูแลดี', 780000, 2020, 42000, 'แดง', '2000', 'auto', 'gasoline', 'นครราชสีมา', 'published', 0, 91),
(6, 9, 33, 'Mercedes-Benz C200 AMG Dynamic ปี 2020', 'mercedes-benz-c200-amg-dynamic-2020', 'Benz C-Class สไตล์ AMG หรูหรา ภายในหนังแท้ ออฟชั่นเต็ม BSM, Lane Keep รถสวย สภาพดี', 1850000, 2020, 55000, 'ดำ', '2000', 'auto', 'gasoline', 'ขอนแก่น', 'published', 1, 167),
(6, 10, 36, 'BMW 320d M Sport ปี 2021 รถศูนย์ BMW', 'bmw-320d-m-sport-2021', 'BMW 3 Series เครื่องดีเซล ประหยัด แรง ชุด M Sport รถศูนย์ ประวัติดี ไม่มีชน', 2150000, 2021, 38000, 'ขาว', '2000', 'auto', 'diesel', 'ขอนแก่น', 'published', 1, 198),
(6, 11, 37, 'Audi A3 1.4 TFSI ปี 2019', 'audi-a3-14-tfsi-2019', 'Audi A3 Sportback เครื่อง Turbo ประหยัด ขับสนุก รูปทรงสวย รถนำเข้า สภาพดี', 1280000, 2019, 62000, 'เทา', '1400', 'auto', 'gasoline', 'ขอนแก่น', 'published', 0, 78),
(2, 1, 7, 'Toyota C-HR 1.8 HV Mid ปี 2022', 'toyota-c-hr-18-hv-mid-2022', 'C-HR Hybrid ดีไซน์สปอร์ต ประหยัดน้ำมันมาก รถใหม่ ยังอยู่ในประกัน ออฟชั่นครบ', 980000, 2022, 18000, 'ขาว', '1800', 'auto', 'hybrid', 'กรุงเทพมหานคร', 'published', 0, 112),
(2, 2, 14, 'Honda HR-V 1.8 EL ปี 2021 ท็อป Sunroof', 'honda-hr-v-18-el-2021', 'HR-V รุ่นท็อป มี Sunroof ภายในหนัง กล้องรอบคัน ขับสบาย ประหยัด รถครอบครัว', 920000, 2021, 28000, 'ดำ', '1800', 'auto', 'gasoline', 'กรุงเทพมหานคร', 'published', 0, 95),
(3, 5, 26, 'Mitsubishi Xpander 1.5 GT ปี 2022 7 ที่นั่ง', 'mitsubishi-xpander-15-gt-2022', 'Xpander รุ่นท็อป 7 ที่นั่ง กว้างขวาง ประหยัดน้ำมัน รถครอบครัว ใช้งานน้อย', 720000, 2022, 25000, 'ขาว', '1500', 'auto', 'gasoline', 'เชียงใหม่', 'published', 0, 88),
(3, 6, 28, 'Nissan Kicks 1.2 VL ปี 2021 e-Power', 'nissan-kicks-12-vl-2021', 'Kicks e-Power ขับเงียบ ประหยัดมาก ออฟชั่นเต็ม ProPilot รถใหม่มาก สภาพดี', 780000, 2021, 32000, 'ส้ม', '1200', 'auto', 'hybrid', 'เชียงใหม่', 'published', 0, 76),
(4, 7, 31, 'Ford Ranger 2.0 Bi-Turbo Wildtrak ปี 2021', 'ford-ranger-20-bi-turbo-wildtrak-2021', 'Ranger รุ่นท็อป Bi-Turbo แรงมาก ออฟชั่นครบ รถสวย ไม่เคยลุย ใช้งานในเมือง', 980000, 2021, 48000, 'ส้ม', '2000', 'auto', 'diesel', 'ชลบุรี', 'published', 0, 134),
(4, 7, 32, 'Ford Everest 2.0 Titanium+ ปี 2020', 'ford-everest-20-titanium-plus-2020', 'Everest ท็อปสุด 4WD ออฟชั่นเต็ม ภายในหรู เบาะหนัง Sunroof รถครอบครัว', 1180000, 2020, 55000, 'ดำ', '2000', 'auto', 'diesel', 'ชลบุรี', 'published', 0, 98),
(5, 1, 3, 'Toyota Yaris 1.2 Sport ปี 2022', 'toyota-yaris-12-sport-2022', 'Yaris รุ่นใหม่ ดีไซน์สปอร์ต ประหยัดน้ำมันมาก รถใช้งานในเมือง ง่าย คล่องตัว', 520000, 2022, 20000, 'แดง', '1200', 'auto', 'gasoline', 'นครราชสีมา', 'published', 0, 67),
(5, 2, 12, 'Honda Jazz 1.5 V ปี 2020', 'honda-jazz-15-v-2020', 'Jazz พื้นที่ใช้สอยเยอะ พับเบาะได้ ประหยัด รถใช้งานทั่วไป สภาพดี', 480000, 2020, 45000, 'ขาว', '1500', 'auto', 'gasoline', 'นครราชสีมา', 'published', 0, 54),
(6, 13, 38, 'Suzuki Swift 1.2 GLX ปี 2021', 'suzuki-swift-12-glx-2021', 'Swift รุ่นท็อป ออฟชั่นครบ ขับสนุก คล่องตัว ประหยัดน้ำมัน รถบ้านมือเดียว', 450000, 2021, 28000, 'ขาว', '1200', 'auto', 'gasoline', 'ขอนแก่น', 'published', 0, 62),
(6, 14, 39, 'MG ZS 1.5 X ปี 2022 รถใหม่', 'mg-zs-15-x-2022', 'MG ZS รุ่นใหม่ ออฟชั่นเต็ม จอใหญ่ Apple CarPlay รถใหม่มาก ราคาดี', 620000, 2022, 15000, 'น้ำเงิน', '1500', 'auto', 'gasoline', 'ขอนแก่น', 'published', 0, 88),
(2, 1, 8, 'Toyota Alphard 2.5 HV ปี 2020 VIP', 'toyota-alphard-25-hv-2020', 'Alphard รถตู้หรู ภายใน VIP เบาะไฟฟ้า ประตูไฟฟ้า รถสวยมาก ไม่มีตำหนิ', 2850000, 2020, 65000, 'ขาวมุก', '2500', 'auto', 'hybrid', 'กรุงเทพมหานคร', 'published', 1, 234),
(3, 4, 23, 'Isuzu MU-X 1.9 The Onyx ปี 2022', 'isuzu-mu-x-19-the-onyx-2022', 'MU-X รุ่นพิเศษ The Onyx ออฟชั่นเต็ม ภายในหรู รถใหม่ ยังอยู่ในประกัน', 1280000, 2022, 22000, 'ดำ', '1900', 'auto', 'diesel', 'เชียงใหม่', 'published', 0, 112),
(4, 9, 34, 'Mercedes-Benz E300 AMG ปี 2019', 'mercedes-benz-e300-amg-2019', 'E-Class เครื่อง 2.0 Turbo แรงมาก ชุด AMG หรูหรา ภายในสภาพดี ออฟชั่นเต็ม', 2450000, 2019, 72000, 'ดำ', '2000', 'auto', 'gasoline', 'ชลบุรี', 'published', 0, 145),
(5, 10, 38, 'BMW X3 xDrive20d M Sport ปี 2020', 'bmw-x3-xdrive20d-m-sport-2020', 'X3 เครื่องดีเซล ประหยัด แรง ชุด M Sport รถครอบครัว ออฟชั่นเต็ม', 2380000, 2020, 48000, 'ขาว', '2000', 'auto', 'diesel', 'นครราชสีมา', 'published', 0, 123),
(6, 15, 40, 'Hyundai Tucson 1.6 Turbo ปี 2022', 'hyundai-tucson-16-turbo-2022', 'Tucson รุ่นใหม่ ดีไซน์ล้ำสมัย เครื่อง Turbo แรง ออฟชั่นเต็ม รถใหม่มาก', 1380000, 2022, 18000, 'เขียว', '1600', 'auto', 'gasoline', 'ขอนแก่น', 'published', 0, 98);

-- Note: Image paths are placeholders. In production, upload actual images.
-- Use placeholder images like: https://placehold.co/800x600/png?text=Car+Image
