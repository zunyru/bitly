<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## About Short url

เป็นเว็บไซต์สำหรับการทำ Short url อย่างสั้น หรือ กำหนดเองได้ มีรายละเอียดดังนี้

- copy url ไปวางที่ช่อง URL 
- จากนั้นกดปุ่ม "SHORTEN"
- เมื่อระบบทำงานสำเร็จจะแสดง URL ที่ได้ทำการ generate มาแล้ว
- กดไปที่ link หรือ ปุ่ม Copy link
- (optional) สามารถ ใส่ ตัวอักษรตามที่ตนเองต้องการได้เช่นกัน เช่น "iPhone13ProMAx"
- Url ที่ถูกสร้างมาจะมีอายุการใช้งาน 1 เดือนนับจากวันที่สร้าง
- Url อาจจะมีแคช

[Admin สามารถ สร้างหรือ แก้ไข URL เองได้ พร้อมทั้งกำหนด วันหมดอายุได้เช่นกัน]

ดูตัวอย่างเว็บ : [Demo](http://damp-atoll-80067.herokuapp.com/)

## หลักการทำงาน

ระบบจาก สร้างรหัส( 7 หลัก) โดย

- จะมีการสลับของตำแหน่งของตัวอักษรทั้งหมด ที่เป็นไปได้
- จะตัดตัวอักษรให้เหลือ 7 หลัก
- เมื่อมีการเรียก จะสร้าง cache เพื่อลดภาระการ qeury ข้อมูล
- มีการตรวจสอบ url ที่ หมดอายุ 
- เมื่อทำการเรียกใช้งานได้ ระบบจะนับจำนวนการเรียกใช้งาน
- มี ฟังก์ชั่น ในการทำ blacklist URL ที่ไม่พึ่งประสงค์

## Git

Clone git ได้จาก : [https://github.com/zunyru/bitly](https://github.com/zunyru/bitly)

### วิธีการติดตั้ง

- ทำการ clone git
- รันคำสั่ง 
```bash
  composer install
```
- รัน migration 
```bash
  php artisan migrate --seed
```
- สร้าง Admin และ ตั้ง password
```bash
  php artisan voyager:admin your@email.com
```

