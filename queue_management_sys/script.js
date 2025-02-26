// document.addEventListener("DOMContentLoaded", fetchQueue);

// function fetchQueue() {
//     document.getElementById("loading").style.display = "block"; 
//     fetch("queue.php")
//         .then(response => response.json())
//         .then(data => {
//             document.getElementById("loading").style.display = "none";
//             const queueTableBody = document.getElementById("queueTableBody");
//             queueTableBody.innerHTML = "";

//             if (data.length === 0) {
//                 queueTableBody.innerHTML = "<tr><td colspan='4'>No customers in queue</td></tr>";
//                 return;
//             }

//             let firstCustomerTime = new Date(data[0].timestamp);

//             data.forEach((customer, index) => {
//                 let estimatedTime = new Date(firstCustomerTime.getTime() + index * 8 * 60000); // 8 min per customer
//                 let formattedTime = estimatedTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

//                 let row = `<tr>
//                     <td>${index + 1}</td>
//                     <td>${customer.name}</td>
//                     <td>${customer.phone}</td>
//                     <td>${formattedTime}</td>
//                 </tr>`;

//                 queueTableBody.innerHTML += row;
//             });
//         })
//         .catch(error => {
//             console.error("Error fetching queue:", error);
//             showMessage("⚠️ Error fetching queue", "red");
//         });
// }

// document.getElementById("addCustomerForm").addEventListener("submit", (e) => {
//     e.preventDefault();
//     // const name = document.getElementById("name").value.trim();                   // Commented for trail
//     // const phone = document.getElementById("phone").value.trim();

//     const name = document.getElementById("name").value.trim();
//     const phone = document.getElementById("phone").value.trim();
//     const email = document.getElementById("email").value.trim();

//     if (!name || !phone || !email) {
//         showMessage("⚠️ Please enter name, phone, and email", "red");
//         return;
//     }

//     fetch("queue.php", {
//         method: "POST",
//         headers: { "Content-Type": "application/json" },
//         body: JSON.stringify({ name, phone, email })
//     })
//     .then(response => response.json())
//     .then(data => {
//         showMessage(data.message || data.error, data.error ? "red" : "green");
//         fetchQueue();
//         document.getElementById("addCustomerForm").reset();
//     })
//     .catch(error => {
//         console.error("Error adding customer:", error);
//         showMessage("⚠️ Error adding customer", "red");
//     });






//     // Commented for trail
//     // if (!name || !phone) {
//     //     showMessage("⚠️ Please enter both name and phone", "red");
//     //     return;
//     // }

//     // fetch("queue.php", {
//     //     method: "POST",
//     //     headers: { "Content-Type": "application/json" },
//     //     body: JSON.stringify({ name, phone })
//     // })
//     // .then(response => response.json())
//     // .then(data => {
//     //     showMessage(data.message || data.error, data.error ? "red" : "green");
//     //     fetchQueue();
//     //     document.getElementById("addCustomerForm").reset();
//     // })
//     // .catch(error => {
//     //     console.error("Error adding customer:", error);
//     //     showMessage("⚠️ Error adding customer", "red");
//     // });
// });

// document.getElementById("serveCustomer").addEventListener("click", () => {
//     fetch("queue.php", { method: "DELETE" })
//         .then(response => response.json())
//         .then(data => {
//             showMessage(data.message || data.error, data.error ? "red" : "green");
//             fetchQueue();
//         })
//         .catch(error => {
//             console.error("Error serving customer:", error);
//             showMessage("⚠️ Error serving customer", "red");
//         });
// });

// Displays a notification message
// function showMessage(message, color) {
//     const msgBox = document.getElementById("messageBox");
//     msgBox.textContent = message;
//     msgBox.style.background = color;
//     msgBox.style.display = "block";
//     setTimeout(() => { msgBox.style.display = "none"; }, 3000);
// }




// 








document.addEventListener("DOMContentLoaded", () => {
    fetchQueue();
    setInterval(fetchQueue, 5000); // Auto-refresh queue every 5 seconds
});

let lastQueueData = []; // Store last fetched queue data to detect changes

function fetchQueue() {
    fetch("queue.php")
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) {
                console.error("Invalid data received:", data);
                return;
            }

            const queueTableBody = document.getElementById("queueTableBody");
            const queueCount = document.getElementById("queueCount");

            // Compare last queue state, update only if there are changes
            if (JSON.stringify(data) === JSON.stringify(lastQueueData)) return;
            lastQueueData = data;

            queueTableBody.innerHTML = "";
            queueCount.textContent = `👥 Total Customers: ${data.length}`;

            if (data.length === 0) {
                queueTableBody.innerHTML = "<tr><td colspan='4'>No customers in queue</td></tr>";
                return;
            }

            let firstCustomerTime = new Date(data[0].timestamp);

            data.forEach((customer, index) => {
                let estimatedTime = new Date(firstCustomerTime.getTime() + index * 8 * 60000);
                let formattedTime = estimatedTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                let row = `<tr>
                    <td>${index + 1}</td>
                    <td>${customer.name}</td>
                    <td>${customer.phone}</td>
                    <td>${formattedTime}</td>
                </tr>`;
                queueTableBody.innerHTML += row;
            });

            // Auto-scroll to the latest entry
            queueTableBody.parentNode.scrollTop = queueTableBody.scrollHeight;
        })
        .catch(error => console.error("Error fetching queue:", error));
}

document.getElementById("addCustomerForm").addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();

    if (!name || !phone || !email) {
        showMessage("⚠️ Please enter name, phone, and email", "red");
        return;
    }

    fetch("queue.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, phone, email })
    })
    .then(response => response.json())
    .then(data => {
        showMessage(data.message || data.error, data.error ? "red" : "green");
        document.getElementById("addCustomerForm").reset();
        fetchQueue(); // 🔥 Instantly update queue after adding customer
        playSound(); // Play notification sound for new customer
    })
    .catch(error => {
        console.error("Error adding customer:", error);
        showMessage("⚠️ Error adding customer", "red");
    });
});

document.getElementById("serveCustomer").addEventListener("click", () => {
    fetch("queue.php", { method: "DELETE" })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, data.error ? "red" : "green");
            fetchQueue(); // 🔥 Instantly update queue after serving customer
        })
        .catch(error => {
            console.error("Error serving customer:", error);
            showMessage("⚠️ Error serving customer", "red");
        });
});

// Display notification message with fade-out effect
function showMessage(message, color) {
    const msgBox = document.getElementById("messageBox");
    msgBox.textContent = message;
    msgBox.style.background = color;
    msgBox.style.display = "block";

    setTimeout(() => {
        msgBox.style.opacity = "1";
        msgBox.style.transition = "opacity 1s ease-out";
        msgBox.style.opacity = "0";
        setTimeout(() => { msgBox.style.display = "none"; }, 1000);
    }, 3000);
}

// Play a sound when a new customer is added
function playSound() {
    const audio = document.getElementById("notificationSound");
    if (audio) {
        audio.play().catch(error => console.log("Audio play error:", error));
    }
}