@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <!-- breadcrumb -->
        <div class="flex items-center gap-2 my-[80px]">
            <a href="{{ route('home') }}" class="text-gray-500">Trang chủ</a>
            <span>/</span>
            <span>Liên hệ</span>
        </div>

        <!-- form liên hệ -->
        <div class="flex gap-[30px]">
            <div class="w-1/3 shadow-[0_0_10px_0_rgba(0,0,0,0.1)] p-[15px]">
                <div class="flex flex-col gap-[16px] p-[35px]">
                    <div class="flex items-center gap-[20px]">
                        <div class="flex items-center justify-center bg-[#BDBDBD] rounded-full w-[50px] h-[50px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="text-[24px] text-[#fff] w-[30px] h-[30px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </div>
                        <span class="text-[24px] font-semibold">Gọi cho chúng tôi</span>
                    </div>
                    <span class="text-[18px]">Chúng tôi phục vụ 24/7, 7 ngày một tuần.</span>
                    <div class="flex items-center gap-[10px]">
                        <div class="flex items-center gap-[10px] text-[18px]">
                            <span class="">Số điện thoại:</span>
                            <span class="">+84 8919576</span>
                        </div>
                    </div>
                </div>
                <div class=" mx-[35px] m-[15px] border-t-[1px] border-[#000]">
                </div>
                <div class="flex flex-col gap-[16px] p-[35px]">
                    <div class="flex items-center gap-[20px]">
                        <div class="flex items-center justify-center bg-[#BDBDBD] rounded-full w-[50px] h-[50px]">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="text-[24px] text-[#fff] w-[30px] h-[30px]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <span class="text-[24px] font-semibold">Viết thư cho Hoa Kỳ</span>
                    </div>
                    <span class="text-[18px]">Hãy điền vào mẫu và chúng tôi sẽ liên hệ với bạn trong vòng 24 giờ.</span>
                    <div class="flex items-center gap-[10px]">
                        <div class="flex flex-col gap-[10px] text-[18px]">
                            <span class="">Emails: customer@exclusive.com</span>
                            <span class="">Emails: support@exclusive.com</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-2/3 shadow-[0_0_10px_0_rgba(0,0,0,0.1)] h-[503px]">
                <form action="">
                    <div class="flex flex-col gap-[16px] p-[35px]">
                        <div class="flex items-center gap-[20px]">
                            <input type="text" placeholder="Tên của bạn" class="w-full p-[20px] bg-[#F5F5F5]">
                            <input type="text" placeholder="Địa chỉ Email của bạn" class="w-full bg-[#F5F5F5] p-[20px]">
                            <input type="text" placeholder="Số điện thoại của bạn" class="w-full bg-[#F5F5F5] p-[20px]">
                        </div>
                        <textarea name="" id="" cols="30" rows="10" placeholder="Nội dung"
                            class="w-full bg-[#F5F5F5] p-[20px]"></textarea>
                        <div class="flex justify-end">
                            <button
                                class="bg-[#000] text-[#fff] text-[18px] font-bold p-[15px] w-[150px] rounded-[4px]">Gửi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
