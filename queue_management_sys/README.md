# Foss_Hackathon
Project Description
The Queue Management System is a web-based application that helps businesses efficiently manage customer queues. It assigns a queue number and an expected service time (8-minute intervals) to each customer. The system updates dynamically when a customer is served, ensuring smooth queue management.

2 Purpose
 Reduces waiting time by providing expected service times.
 Improves customer experience by displaying real-time queue updates.
 Automates queue handling, reducing manual effort.
 Enhances efficiency for businesses like banks, hospitals, and offices.

3 Tech Stack
 Frontend: HTML, CSS, JavaScript (UI & User Interaction)
 Backend: PHP (Handles requests & database operations)
 Database: MySQL (Stores customer data & queue timestamps)

4 Features
 Add customers to queue with name & phone number.
 Display real-time queue in a table format.
 Predict service time (based on 8-minute intervals).
 Automatically remove served customers & update queue.

5 Working
## **Step-by-Step Working of the Queue Management System**  

---

### **🔹 Step 1: Customer Joins the Queue**  
1️⃣ The user enters their **name** and **phone number** in the input form.  
2️⃣ Clicks the **"Add to Queue"** button.  
3️⃣ The system **stores the data in the MySQL database** and assigns:  
   - **Queue number** (based on order).  
   - **Current timestamp** (for service time calculation).  

---

### **🔹 Step 2: Display the Queue in Table Format**  
1️⃣ The system fetches **all customers from the database** in order.  
2️⃣ Displays the **queue list in a table** with:  
   - **Queue Number**  
   - **Customer Name**  
   - **Phone Number**  
   - **Expected Service Time** (calculated at **8 minutes per customer**).  
3️⃣ The table updates automatically whenever a **new customer is added**.  

---

### **🔹 Step 3: Calculate Expected Service Time**  
1️⃣ The **first customer’s timestamp** is taken as the base time.  
2️⃣ Each customer's **expected service time** is calculated as:  
   ```
   Expected Time = First Customer Timestamp + (Queue Position - 1) × 8 minutes
   ```
3️⃣ This ensures **accurate service time predictions** for all customers.  

---

### **🔹 Step 4: Serving a Customer**  
1️⃣ Staff clicks the **"Serve Next Customer"** button.  
2️⃣ The system **removes the first customer from the database**.  
3️⃣ The **remaining customers move up** in the queue.  
4️⃣ The **expected service times update automatically**.  

---

### **🔹 Step 5: Queue Updates in Real Time**  
1️⃣ The system **automatically refreshes** the queue display.  
2️⃣ Customers can always **see their updated position and service time**.  
3️⃣ The process repeats for every new customer added or served.  

---

### **🚀 Final Outcome**  
✅ **Customers know their queue number & expected service time.**  
✅ **Business staff can manage queues efficiently.**  
✅ **The system updates in real-time for smooth operations.**  

🔹 **This project automates queue handling, improving efficiency & customer experience!** 🔹
