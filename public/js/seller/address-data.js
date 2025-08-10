// Dữ liệu địa chỉ Việt Nam - Thay thế API
const VIETNAM_ADDRESS_DATA = {
    provinces: [
        { code: "01", name: "Hà Nội" },
        { code: "02", name: "Hà Giang" },
        { code: "04", name: "Cao Bằng" },
        { code: "06", name: "Bắc Kạn" },
        { code: "08", name: "Tuyên Quang" },
        { code: "10", name: "Lào Cai" },
        { code: "11", name: "Điện Biên" },
        { code: "12", name: "Lai Châu" },
        { code: "14", name: "Sơn La" },
        { code: "15", name: "Yên Bái" },
        { code: "17", name: "Hòa Bình" },
        { code: "19", name: "Thái Nguyên" },
        { code: "20", name: "Lạng Sơn" },
        { code: "22", name: "Quảng Ninh" },
        { code: "24", name: "Bắc Giang" },
        { code: "25", name: "Phú Thọ" },
        { code: "26", name: "Vĩnh Phúc" },
        { code: "27", name: "Quảng Ninh" },
        { code: "30", name: "Hải Dương" },
        { code: "31", name: "Hải Phòng" },
        { code: "33", name: "Hưng Yên" },
        { code: "34", name: "Thái Bình" },
        { code: "35", name: "Hà Nam" },
        { code: "36", name: "Nam Định" },
        { code: "37", name: "Ninh Bình" },
        { code: "38", name: "Thanh Hóa" },
        { code: "40", name: "Nghệ An" },
        { code: "42", name: "Hà Tĩnh" },
        { code: "44", name: "Quảng Bình" },
        { code: "45", name: "Quảng Trị" },
        { code: "46", name: "Thừa Thiên Huế" },
        { code: "48", name: "Đà Nẵng" },
        { code: "49", name: "Quảng Nam" },
        { code: "51", name: "Quảng Ngãi" },
        { code: "52", name: "Bình Định" },
        { code: "54", name: "Phú Yên" },
        { code: "56", name: "Khánh Hòa" },
        { code: "58", name: "Ninh Thuận" },
        { code: "60", name: "Bình Thuận" },
        { code: "62", name: "Kon Tum" },
        { code: "64", name: "Gia Lai" },
        { code: "66", name: "Đắk Lắk" },
        { code: "67", name: "Đắk Nông" },
        { code: "68", name: "Lâm Đồng" },
        { code: "70", name: "Bình Phước" },
        { code: "72", name: "Tây Ninh" },
        { code: "74", name: "Bình Dương" },
        { code: "75", name: "Đồng Nai" },
        { code: "77", name: "Bà Rịa - Vũng Tàu" },
        { code: "79", name: "TP. Hồ Chí Minh" },
        { code: "80", name: "Long An" },
        { code: "82", name: "Tiền Giang" },
        { code: "83", name: "Bến Tre" },
        { code: "84", name: "Trà Vinh" },
        { code: "86", name: "Vĩnh Long" },
        { code: "87", name: "Đồng Tháp" },
        { code: "89", name: "An Giang" },
        { code: "91", name: "Kiên Giang" },
        { code: "92", name: "Cần Thơ" },
        { code: "93", name: "Hậu Giang" },
        { code: "94", name: "Sóc Trăng" },
        { code: "95", name: "Bạc Liêu" },
        { code: "96", name: "Cà Mau" }
    ],
    
    districts: {
        "01": [ // Hà Nội
            { code: "001", name: "Ba Đình" },
            { code: "002", name: "Hoàn Kiếm" },
            { code: "003", name: "Tây Hồ" },
            { code: "004", name: "Long Biên" },
            { code: "005", name: "Cầu Giấy" },
            { code: "006", name: "Đống Đa" },
            { code: "007", name: "Hai Bà Trưng" },
            { code: "008", name: "Hoàng Mai" },
            { code: "009", name: "Thanh Xuân" },
            { code: "016", name: "Sóc Sơn" },
            { code: "017", name: "Đông Anh" },
            { code: "018", name: "Gia Lâm" },
            { code: "019", name: "Nam Từ Liêm" },
            { code: "020", name: "Thanh Trì" },
            { code: "021", name: "Bắc Từ Liêm" },
            { code: "024", name: "Hà Đông" },
            { code: "025", name: "Mê Linh" },
            { code: "026", name: "Sơn Tây" },
            { code: "027", name: "Ba Vì" },
            { code: "028", name: "Phúc Thọ" },
            { code: "029", name: "Đan Phượng" },
            { code: "030", name: "Hoài Đức" },
            { code: "031", name: "Quốc Oai" },
            { code: "032", name: "Thạch Thất" },
            { code: "033", name: "Chương Mỹ" },
            { code: "034", name: "Thanh Oai" },
            { code: "035", name: "Thường Tín" },
            { code: "036", name: "Phú Xuyên" },
            { code: "037", name: "Ứng Hòa" },
            { code: "038", name: "Mỹ Đức" }
        ],
        "79": [ // TP. Hồ Chí Minh
            { code: "760", name: "Quận 1" },
            { code: "761", name: "Quận 2" },
            { code: "762", name: "Quận 3" },
            { code: "763", name: "Quận 4" },
            { code: "764", name: "Quận 5" },
            { code: "765", name: "Quận 6" },
            { code: "766", name: "Quận 7" },
            { code: "767", name: "Quận 8" },
            { code: "768", name: "Quận 9" },
            { code: "769", name: "Quận 10" },
            { code: "770", name: "Quận 11" },
            { code: "771", name: "Quận 12" },
            { code: "772", name: "Quận Tân Bình" },
            { code: "773", name: "Quận Bình Tân" },
            { code: "774", name: "Quận Tân Phú" },
            { code: "775", name: "Quận Phú Nhuận" },
            { code: "776", name: "Quận Gò Vấp" },
            { code: "777", name: "Quận Bình Thạnh" },
            { code: "778", name: "Quận Thủ Đức" },
            { code: "783", name: "Huyện Củ Chi" },
            { code: "784", name: "Huyện Hóc Môn" },
            { code: "785", name: "Huyện Bình Chánh" },
            { code: "786", name: "Huyện Nhà Bè" },
            { code: "787", name: "Huyện Cần Giờ" }
        ],
        "48": [ // Đà Nẵng
            { code: "490", name: "Quận Hải Châu" },
            { code: "491", name: "Quận Thanh Khê" },
            { code: "492", name: "Quận Sơn Trà" },
            { code: "493", name: "Quận Ngũ Hành Sơn" },
            { code: "494", name: "Quận Liên Chiểu" },
            { code: "495", name: "Quận Cẩm Lệ" },
            { code: "497", name: "Huyện Hòa Vang" },
            { code: "498", name: "Huyện Hoàng Sa" }
        ],
        "31": [ // Hải Phòng
            { code: "311", name: "Quận Hồng Bàng" },
            { code: "312", name: "Quận Ngô Quyền" },
            { code: "313", name: "Quận Lê Chân" },
            { code: "314", name: "Quận Hải An" },
            { code: "315", name: "Quận Kiến An" },
            { code: "316", name: "Quận Đồ Sơn" },
            { code: "317", name: "Quận Dương Kinh" },
            { code: "323", name: "Huyện Thủy Nguyên" },
            { code: "324", name: "Huyện An Dương" },
            { code: "325", name: "Huyện An Lão" },
            { code: "326", name: "Huyện Kiến Thụy" },
            { code: "327", name: "Huyện Tiên Lãng" },
            { code: "328", name: "Huyện Vĩnh Bảo" },
            { code: "329", name: "Huyện Cát Hải" },
            { code: "330", name: "Huyện Bạch Long Vĩ" }
        ],
        "74": [ // Bình Dương
            { code: "718", name: "Thành phố Thủ Dầu Một" },
            { code: "719", name: "Thành phố Dĩ An" },
            { code: "720", name: "Thành phố Thuận An" },
            { code: "721", name: "Thành phố Bình Dương" },
            { code: "722", name: "Thành phố Tân Uyên" },
            { code: "723", name: "Thành phố Bến Cát" },
            { code: "724", name: "Huyện Phú Giáo" },
            { code: "725", name: "Huyện Bắc Tân Uyên" },
            { code: "726", name: "Huyện Dầu Tiếng" }
        ],
        "75": [ // Đồng Nai
            { code: "731", name: "Thành phố Biên Hòa" },
            { code: "732", name: "Thành phố Long Khánh" },
            { code: "734", name: "Huyện Tân Phú" },
            { code: "735", name: "Huyện Vĩnh Cửu" },
            { code: "736", name: "Huyện Định Quán" },
            { code: "737", name: "Huyện Trảng Bom" },
            { code: "738", name: "Huyện Thống Nhất" },
            { code: "739", name: "Huyện Cẩm Mỹ" },
            { code: "740", name: "Huyện Long Thành" },
            { code: "741", name: "Huyện Xuân Lộc" },
            { code: "742", name: "Huyện Nhơn Trạch" }
        ],
        "77": [ // Bà Rịa - Vũng Tàu
            { code: "747", name: "Thành phố Vũng Tàu" },
            { code: "748", name: "Thành phố Bà Rịa" },
            { code: "750", name: "Huyện Châu Đức" },
            { code: "751", name: "Huyện Xuyên Mộc" },
            { code: "752", name: "Huyện Long Điền" },
            { code: "753", name: "Huyện Đất Đỏ" },
            { code: "754", name: "Huyện Tân Thành" },
            { code: "755", name: "Huyện Côn Đảo" }
        ],
        "92": [ // Cần Thơ
            { code: "866", name: "Quận Ninh Kiều" },
            { code: "867", name: "Quận Ô Môn" },
            { code: "868", name: "Quận Bình Thủy" },
            { code: "869", name: "Quận Cái Răng" },
            { code: "870", name: "Quận Thốt Nốt" },
            { code: "871", name: "Huyện Vĩnh Thạnh" },
            { code: "872", name: "Huyện Cờ Đỏ" },
            { code: "873", name: "Huyện Phong Điền" },
            { code: "874", name: "Huyện Thới Lai" }
        ]
    },
    
    wards: {
        "001": [ // Ba Đình - Hà Nội
            { code: "00001", name: "Phúc Xá" },
            { code: "00004", name: "Trúc Bạch" },
            { code: "00006", name: "Vĩnh Phúc" },
            { code: "00007", name: "Cống Vị" },
            { code: "00008", name: "Liễu Giai" },
            { code: "00010", name: "Nguyễn Trung Trực" },
            { code: "00013", name: "Quán Thánh" },
            { code: "00016", name: "Ngọc Hà" },
            { code: "00019", name: "Điện Biên" },
            { code: "00022", name: "Đội Cấn" },
            { code: "00025", name: "Ngọc Khánh" },
            { code: "00028", name: "Kim Mã" },
            { code: "00031", name: "Giảng Võ" },
            { code: "00034", name: "Thành Công" }
        ],
        "760": [ // Quận 1 - TP.HCM
            { code: "26734", name: "Phường Tân Định" },
            { code: "26737", name: "Phường Đa Kao" },
            { code: "26740", name: "Phường Bến Nghé" },
            { code: "26743", name: "Phường Bến Thành" },
            { code: "26746", name: "Phường Nguyễn Thái Bình" },
            { code: "26749", name: "Phường Phạm Ngũ Lão" },
            { code: "26752", name: "Phường Cầu Ông Lãnh" },
            { code: "26755", name: "Phường Cô Giang" },
            { code: "26758", name: "Phường Nguyễn Cư Trinh" },
            { code: "26761", name: "Phường Cầu Kho" }
        ],
        "490": [ // Hải Châu - Đà Nẵng
            { code: "20305", name: "Phường Thanh Bình" },
            { code: "20308", name: "Phường Thuận Phước" },
            { code: "20311", name: "Phường Thạch Thang" },
            { code: "20314", name: "Phường Hải Châu I" },
            { code: "20317", name: "Phường Hải Châu II" },
            { code: "20320", name: "Phường Phước Ninh" },
            { code: "20323", name: "Phường Hòa Thuận Tây" },
            { code: "20326", name: "Phường Hòa Thuận Đông" },
            { code: "20329", name: "Phường Nam Dương" },
            { code: "20332", name: "Phường Bình Hiên" },
            { code: "20335", name: "Phường Bình Thuận" },
            { code: "20338", name: "Phường Hòa Cường Bắc" },
            { code: "20341", name: "Phường Hòa Cường Nam" }
        ],
        "311": [ // Hồng Bàng - Hải Phòng
            { code: "11347", name: "Phường Hạ Lý" },
            { code: "11350", name: "Phường Trần Thành Ngọ" },
            { code: "11353", name: "Phường Hùng Vương" },
            { code: "11356", name: "Phường Sở Dầu" },
            { code: "11359", name: "Phường Thượng Lý" },
            { code: "11362", name: "Phường Hạ Lý" },
            { code: "11365", name: "Phường Minh Khai" },
            { code: "11368", name: "Phường Trại Chuối" },
            { code: "11371", name: "Phường Hoàng Văn Thụ" },
            { code: "11374", name: "Phường Phan Bội Châu" }
        ],
        "866": [ // Ninh Kiều - Cần Thơ
            { code: "29956", name: "Phường An Nghiệp" },
            { code: "29957", name: "Phường An Cư" },
            { code: "29959", name: "Phường Tân An" },
            { code: "29960", name: "Phường An Hoà" },
            { code: "29962", name: "Phường Thới Bình" },
            { code: "29963", name: "Phường An Lạc" },
            { code: "29965", name: "Phường An Phú" },
            { code: "29966", name: "Phường Xuân Khánh" },
            { code: "29968", name: "Phường Hưng Thạnh" },
            { code: "29969", name: "Phường An Khánh" },
            { code: "29971", name: "Phường An Bình" }
        ],
        "718": [ // Thủ Dầu Một - Bình Dương
            { code: "25705", name: "Phường Chánh Mỹ" },
            { code: "25708", name: "Phường Chánh Nghĩa" },
            { code: "25709", name: "Phường Định Hoà" },
            { code: "25710", name: "Phường Hiệp An" },
            { code: "25711", name: "Phường Hiệp Thành" },
            { code: "25712", name: "Phường Hoà Phú" },
            { code: "25713", name: "Phường Phú Cường" },
            { code: "25714", name: "Phường Phú Hòa" },
            { code: "25715", name: "Phường Phú Lợi" },
            { code: "25716", name: "Phường Phú Mỹ" },
            { code: "25717", name: "Phường Phú Tân" },
            { code: "25718", name: "Phường Phú Thọ" },
            { code: "25719", name: "Phường Tân An" },
            { code: "25720", name: "Phường Tương Bình Hiệp" }
        ],

    }
};

// Hàm lấy quận/huyện theo tỉnh
function getDistrictsByProvince(provinceCode) {
    return VIETNAM_ADDRESS_DATA.districts[provinceCode] || [];
}

// Hàm lấy phường/xã theo quận/huyện
function getWardsByDistrict(districtCode) {
    return VIETNAM_ADDRESS_DATA.wards[districtCode] || [];
}

// Hàm lấy tên tỉnh theo mã
function getProvinceName(provinceCode) {
    const province = VIETNAM_ADDRESS_DATA.provinces.find(p => p.code === provinceCode);
    return province ? province.name : '';
}

// Hàm lấy tên quận/huyện theo mã
function getDistrictName(districtCode) {
    for (const provinceCode in VIETNAM_ADDRESS_DATA.districts) {
        const district = VIETNAM_ADDRESS_DATA.districts[provinceCode].find(d => d.code === districtCode);
        if (district) return district.name;
    }
    return '';
}

// Hàm lấy tên phường/xã theo mã
function getWardName(wardCode) {
    for (const districtCode in VIETNAM_ADDRESS_DATA.wards) {
        const ward = VIETNAM_ADDRESS_DATA.wards[districtCode].find(w => w.code === wardCode);
        if (ward) return ward.name;
    }
    return '';
}
