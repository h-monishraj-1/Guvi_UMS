# 📝 User Management System (UMS)

### 🚀 Problem Statement

To create a secure and responsive **User Management System** with the following flow:

> 🧑‍💻 **Register** ➡️ 🔐 **Login** ➡️ 🙋‍♂️ **Profile**

### 📌 Features

- User can **register** using a signup form.
- Registered users can **login** with their credentials.
- After login, redirect the user to their **profile page** containing:
  - Full Name
  - Age
  - Date of Birth
  - Contact Information
  - Address
- Users can **update** their profile details.

---

### 🧠 Guidelines Followed

- 📄 HTML, CSS, JavaScript, and PHP code are in **separate files**.
- 💬 Used **jQuery AJAX** to communicate with the backend — _no form submissions were used!_
- 🎨 Designed the form using **Bootstrap** to ensure responsiveness.
- 💅 Added **custom CSS** for styling enhancements.

#### 🛠 Backend Requirements

- 🐬 Used **MySQL** to store:
  - Registration data
  - User profile details
- 🔐 All MySQL queries uses **Prepared Statements** (❌ No raw SQL).
- ❌ PHP sessions are **not** used.
- 🗃️ Used **Redis** to store **session information** on the backend.
- 🌐 Maintained user login sessions using **browser localStorage** only.

---

### 🧰 Tech Stack

| 🔧 Technology | 💼 Purpose                  |
|--------------|----------------------------|
| HTML         | Page structure             |
| CSS          | Styling                    |
| JavaScript   | Frontend logic             |
| jQuery AJAX  | Asynchronous data handling |
| PHP          | Server-side scripting      |
| MySQL        | Relational database        |
| Redis        | Session storage            |
| MongoDB      | Optional document storage  |

---

### 🏁 Getting Started

1. 📥 Clone this repository.
2. 🔧 Set up your **MySQL** and **Redis** servers.
3. 🛠 Configure the database connection in `php/db_config.php`.
4. 🌐 Open `index.html` or `register.html` in a browser to begin testing.

---

### ✅ Best Practices Followed

- ✅ Secure SQL interactions using **prepared statements**
- ✅ Session handled using **localStorage** and **Redis**
- ✅ Responsive UI with **Bootstrap**
- ✅ Asynchronous communication via **jQuery AJAX**
- ✅ Clean separation of **frontend** and **backend** logic

---

> 💡 This project showcases an end-to-end secure, modular, and scalable user authentication flow using a modern full-stack setup.

![image](https://github.com/user-attachments/assets/09c9d9ed-6094-4cc9-bff2-b17c220d7f9c)

![image](https://github.com/user-attachments/assets/e0722310-c71f-4995-bd68-979faae2d415)

![image](https://github.com/user-attachments/assets/64256b35-ea0f-4a24-9a53-85372760fc80)

![image](https://github.com/user-attachments/assets/8acf5b4f-5da6-4dd9-924c-d873733af135)

![image](https://github.com/user-attachments/assets/d692cf2f-5051-429f-a019-95acc0c514be)

