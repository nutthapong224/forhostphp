
# Deploy PHP + MariaDB + phpMyAdmin with Docker Compose on Ubuntu 24.04

## รายละเอียดโปรเจกต์
- ใช้ MariaDB (Bitnami) เป็นฐานข้อมูล  
- ใช้ phpMyAdmin สำหรับจัดการฐานข้อมูลผ่านเว็บ  
- ใช้ PHP 8.2 + Apache รันเว็บ PHP  
- เชื่อมต่อ PHP กับฐานข้อมูลผ่าน mysqli extension  
- โฟลเดอร์ `./php` เป็นที่เก็บไฟล์ PHP และไฟล์ SQL สำหรับ initial database

---

## ขั้นตอนติดตั้งและรันบน Ubuntu 24.04

### 1. ติดตั้ง Docker และ Docker Compose (ถ้ายังไม่ได้ติดตั้ง)

```bash
# ติดตั้ง dependencies
sudo apt update
sudo apt install -y ca-certificates curl gnupg lsb-release

# เพิ่ม Docker's official GPG key
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# เพิ่ม repository
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# ติดตั้ง Docker Engine
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# เปิดใช้งาน
sudo systemctl enable --now docker

```

ตรวจสอบเวอร์ชัน Docker และ Compose:

```bash
docker --version
docker compose --version
```

---

### 2. เตรียมโฟลเดอร์โปรเจกต์

สมมติโฟลเดอร์โปรเจกต์ชื่อ `my-php-app`

```bash
mkdir -p ~/forhostphp
cd ~/forhostphp
```

วางไฟล์ `docker-compose.yaml` และโฟลเดอร์ `php` ที่มีไฟล์ PHP และไฟล์ SQL สำหรับ initial database ไว้ที่นี่

---

### 3. รัน Docker Compose

```bash
sudo docker compose up -d --build
```

คำสั่งนี้จะดาวน์โหลดอิมเมจที่จำเป็น สร้าง container และรันใน background

---

### 4. ตรวจสอบสถานะ container

```bash
sudo docker compose ps
```

- `apex-db2` (MariaDB) ควรอยู่ในสถานะ `healthy`  
- `apex-phpmyadmin-2` พร้อมใช้งานที่พอร์ต 8080  
- `php-app` พร้อมใช้งานที่พอร์ต 80

---

### 5. เข้าถึงบริการ

- เข้าหน้าเว็บ PHP ของคุณผ่าน:  
  http://localhost หรือ http://<your-server-ip>  

- เข้าหน้า phpMyAdmin เพื่อจัดการฐานข้อมูลผ่านเว็บ:  
  http://localhost:8080 หรือ http://<your-server-ip>:8080  

ใช้ username/password ที่กำหนดใน `docker-compose.yaml` คือ `testuser` / `testpass`

---

### 6. การหยุดและลบ container

```bash
sudo docker compose down
```

---

### หมายเหตุ

- ไฟล์และฐานข้อมูลจะถูกเก็บในโฟลเดอร์ `./mariadb_data` เพื่อเก็บข้อมูลถาวร  
- หากต้องการเริ่มฐานข้อมูลใหม่ให้ลบโฟลเดอร์ `mariadb_data` ก่อนแล้วรันใหม่  
- โฟลเดอร์ `./php` จะแมปไปยัง `/var/www/html` ของ container PHP  

---

## ตัวอย่างคำสั่งที่ใช้งานบ่อย

- ดู log ของ container MariaDB

  ```bash
  sudo docker logs apex-db2 -f
  ```

- เข้าไป shell container PHP

  ```bash
  sudo docker exec -it php-app bash
  ```

---

หากมีคำถามหรือปัญหา สามารถแจ้งได้ครับ!
