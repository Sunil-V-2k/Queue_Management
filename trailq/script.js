// // // document.addEventListener("DOMContentLoaded", fetchQueue);

// // // function fetchQueue() {
// // //     fetch("queue.php")
// // //         .then(response => response.json())
// // //         .then(data => {
// // //             const queueList = document.getElementById("queueList");
// // //             queueList.innerHTML = "";
// // //             data.forEach((customer, index) => {
// // //                 queueList.innerHTML += `<li class="list-group-item">${index + 1}. ${customer.name} (${customer.phone})</li>`;
// // //             });
// // //         })
// // //         .catch(error => {
// // //             console.error("Error fetching queue:", error);
// // //             alert("Error fetching queue.");
// // //         });
// // // }

// // // document.getElementById("addCustomerForm").addEventListener("submit", (e) => {
// // //     e.preventDefault();
// // //     const name = document.getElementById("name").value.trim();
// // //     const phone = document.getElementById("phone").value.trim();

// // //     if (!name || !phone) {
// // //         alert("Please enter both name and phone.");
// // //         return;
// // //     }

// // //     fetch("queue.php", {
// // //         method: "POST",
// // //         headers: { "Content-Type": "application/json" },
// // //         body: JSON.stringify({ name, phone })
// // //     })
// // //     .then(response => response.json())
// // //     .then(data => {
// // //         alert(data.message || data.error);
// // //         fetchQueue();
// // //         document.getElementById("addCustomerForm").reset();
// // //     })
// // //     .catch(error => {
// // //         console.error("Error adding customer:", error);
// // //         alert("Error adding customer.");
// // //     });
// // // });

// // // document.getElementById("serveCustomer").addEventListener("click", () => {
// // //     fetch("queue.php", { method: "DELETE" })
// // //         .then(response => response.json())
// // //         .then(data => {
// // //             alert(data.message || data.error);
// // //             fetchQueue();
// // //         })
// // //         .catch(error => {
// // //             console.error("Error serving customer:", error);
// // //             alert("Error serving customer.");
// // //         });
// // // });


// // document.addEventListener("DOMContentLoaded", fetchQueue);

// // function fetchQueue() {
// //     fetch("queue.php")
// //         .then(response => response.json())
// //         .then(data => {
// //             const queueList = document.getElementById("queueList");
// //             queueList.innerHTML = "";
// //             data.forEach((customer, index) => {
// //                 queueList.innerHTML += `
// //                     <li class="list-group-item">
// //                         ${index + 1}. ${customer.name} (${customer.phone}) 
// //                         <br><span class="timestamp">Added on: ${customer.timestamp}</span>
// //                     </li>`;
// //             });
// //         })
// //         .catch(error => {
// //             console.error("Error fetching queue:", error);
// //             alert("Error fetching queue.");
// //         });
// // }

// // document.getElementById("addCustomerForm").addEventListener("submit", (e) => {
// //     e.preventDefault();
// //     const name = document.getElementById("name").value.trim();
// //     const phone = document.getElementById("phone").value.trim();

// //     if (!name || !phone) {
// //         alert("Please enter both name and phone.");
// //         return;
// //     }

// //     fetch("queue.php", {
// //         method: "POST",
// //         headers: { "Content-Type": "application/json" },
// //         body: JSON.stringify({ name, phone })
// //     })
// //     .then(response => response.json())
// //     .then(data => {
// //         alert(data.message || data.error);
// //         fetchQueue();
// //         document.getElementById("addCustomerForm").reset();
// //     })
// //     .catch(error => {
// //         console.error("Error adding customer:", error);
// //         alert("Error adding customer.");
// //     });
// // });

// // document.getElementById("serveCustomer").addEventListener("click", () => {
// //     fetch("queue.php", { method: "DELETE" })
// //         .then(response => response.json())
// //         .then(data => {
// //             alert(data.message || data.error);
// //             fetchQueue();
// //         })
// //         .catch(error => {
// //             console.error("Error serving customer:", error);
// //             alert("Error serving customer.");
// //         });
// // });




// document.addEventListener("DOMContentLoaded", fetchQueue);

// function fetchQueue() {
//     document.getElementById("loading").style.display = "block"; // Show loading
//     fetch("queue.php")
//         .then(response => response.json())
//         .then(data => {
//             document.getElementById("loading").style.display = "none"; // Hide loading
//             const queueList = document.getElementById("queueList");
//             queueList.innerHTML = "";

//             if (data.length === 0) {
//                 queueList.innerHTML = "<p>No customers in queue</p>";
//                 return;
//             }

//             data.forEach((customer, index) => {
//                 queueList.innerHTML += `
//                     <li class="list-group-item">
//                         <strong>${index + 1}. ${customer.name} (${customer.phone})</strong>
//                         <br><span class="timestamp">Added: ${formatTimestamp(customer.timestamp)}</span>
//                     </li>`;
//             });
//         })
//         .catch(error => {
//             console.error("Error fetching queue:", error);
//             showMessage("Error fetching queue", "red");
//         });
// }

// document.getElementById("addCustomerForm").addEventListener("submit", (e) => {
//     e.preventDefault();
//     const name = document.getElementById("name").value.trim();
//     const phone = document.getElementById("phone").value.trim();

//     if (!name || !phone) {
//         showMessage("Please enter both name and phone", "red");
//         return;
//     }

//     fetch("queue.php", {
//         method: "POST",
//         headers: { "Content-Type": "application/json" },
//         body: JSON.stringify({ name, phone })
//     })
//     .then(response => response.json())
//     .then(data => {
//         showMessage(data.message || data.error, data.error ? "red" : "green");
//         fetchQueue();
//         document.getElementById("addCustomerForm").reset();
//     })
//     .catch(error => {
//         console.error("Error adding customer:", error);
//         showMessage("Error adding customer", "red");
//     });
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
//             showMessage("Error serving customer", "red");
//         });
// });

// // Converts MySQL timestamp to readable format
// function formatTimestamp(timestamp) {
//     const date = new Date(timestamp);
//     return date.toLocaleString(); // Formats to "MM/DD/YYYY, HH:MM AM/PM"
// }

// // Displays a notification message
// function showMessage(message, color) {
//     const msgBox = document.getElementById("messageBox");
//     msgBox.textContent = message;
//     msgBox.style.background = color;
//     msgBox.style.display = "block";
//     setTimeout(() => { msgBox.style.display = "none"; }, 3000);
// }





document.addEventListener("DOMContentLoaded", fetchQueue);

function fetchQueue() {
    document.getElementById("loading").style.display = "block"; // Show loading
    fetch("queue.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("loading").style.display = "none"; // Hide loading
            const queueList = document.getElementById("queueList");
            queueList.innerHTML = "";

            if (data.length === 0) {
                queueList.innerHTML = "<p>No customers in queue</p>";
                return;
            }

            data.forEach((customer, index) => {
                queueList.innerHTML += `
                    <li class="list-group-item">
                        <strong>${index + 1}. ${customer.name} (${customer.phone})</strong>
                        <br><span class="timestamp">⏳ Added: ${formatTimestamp(customer.timestamp)}</span>
                    </li>`;
            });
        })
        .catch(error => {
            console.error("Error fetching queue:", error);
            showMessage("⚠️ Error fetching queue", "red");
        });
}

document.getElementById("addCustomerForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();

    if (!name || !phone) {
        showMessage("⚠️ Please enter both name and phone", "red");
        return;
    }

    fetch("queue.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, phone })
    })
    .then(response => response.json())
    .then(data => {
        showMessage(data.message || data.error, data.error ? "red" : "green");
        fetchQueue();
        document.getElementById("addCustomerForm").reset();
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
            fetchQueue();
        })
        .catch(error => {
            console.error("Error serving customer:", error);
            showMessage("⚠️ Error serving customer", "red");
        });
});

// Converts MySQL timestamp to readable format
function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString(); // Formats to "MM/DD/YYYY, HH:MM AM/PM"
}

// Displays a notification message
function showMessage(message, color) {
    const msgBox = document.getElementById("messageBox");
    msgBox.textContent = message;
    msgBox.style.background = color;
    msgBox.style.display = "block";
    setTimeout(() => { msgBox.style.display = "none"; }, 3000);
}
