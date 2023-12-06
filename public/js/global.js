// Login implementation
document.querySelectorAll(".form").forEach((form) => {
  form.addEventListener("click", function (e) {
    let val = e.target.closest(
      `#${e.target.getAttribute(`data-for-${form.id}`)}`
    );

    if (!e.target.closest(`.${e.target.getAttribute(`data-for-${form.id}`)}`))
      return;

    e.currentTarget.style.display = "none";

    if (!e.currentTarget.classList.contains("signup")) {
      val.nextElementSibling.style.display = "block";
    } else {
      val.previousElementSibling.style.display = "block";
    }
  });
});

// New booking implementation
let overlay = document.querySelector(".overlay");
let overview = document.querySelector(".overview");
let newBooking = document.querySelector(".new-booking");
let closeNew = document.querySelector(".close-new");
let bookNew = document.querySelector(".book-new");
let confirmOverlay = document.querySelector(".confirm-parent");
let popupBtns = [closeNew, bookNew];
let newPopup = [overlay, newBooking];

if (document.querySelector(".controls button")) {
  document.querySelector(".controls button").addEventListener("click", () => {
    newPopup.forEach((popup) => {
      if (!popup.classList.contains("active")) {
        popup.classList.add("active");
        if (!overview) return;
        overview.style.pointerEvents = "none";
      }
    });

    popupBtns.forEach((popup) => {
      popup.addEventListener("click", () => {
        newPopup.forEach((popup) => {
          popup.classList.remove("active");
        });
      });
    });
  });
}

// Transaction state implementation
let statusEl = document
  .querySelectorAll(".activities #status")
  .forEach((status) => {
    let editStatus = status.textContent.toLowerCase();

    status.previousElementSibling.style.backgroundColor =
      editStatus === "successful"
        ? "#08a129cc"
        : editStatus === "pending"
        ? "#ffae00cc"
        : editStatus === "in-progress"
        ? "#f4da6f"
        : "#f83600cc";
  });

// Modal immplementation
document.querySelectorAll(".open-modal").forEach((openBtn) =>
  openBtn.addEventListener("click", (e) => {
    if (
      e.currentTarget.parentElement.previousElementSibling.innerText ===
        "Successful" &&
      e.currentTarget.closest("img").getAttribute("data-for-modal") ===
        "edit-booking"
    )
      return;

    if (
      e.currentTarget.parentElement.previousElementSibling.innerText ===
        "Failed" &&
      e.currentTarget.closest("img").getAttribute("data-for-modal") ===
        "edit-booking"
    )
      return;

    const forModal = openBtn.getAttribute("data-for-modal");

    document.querySelector(`#${forModal}`).classList.add("active");

    // for deleting booking with delete-booking-id
    const targetElement = document.querySelector(
      `#${openBtn.getAttribute("data-for-el")}`
    );

    const targetValue = JSON.parse(openBtn.getAttribute("data-value"));

    const delTargetValue = JSON.parse(
      JSON.stringify(openBtn.getAttribute("data-for-del"))
    );

    if (targetElement) {
      targetElement.value = delTargetValue;
    } else if (typeof targetValue === "object") {
      console.log(targetValue);
      targetValue?.forEach((data) => {
        const element = document.querySelector(`#${data?.["el-id"]}`);

        if (element?.tagName === "INPUT") {
          element.value = data?.value;
        } else if (element?.tagName === "SELECT") {
          element.value = data?.value;
        } else {
          element.textContent = data?.value;
        }
      });
    }
  })
);
// Close modal
document.querySelectorAll(".close-modal").forEach((closeBtn) =>
  closeBtn.addEventListener("click", () => {
    const forModal = closeBtn.getAttribute("data-for-modal");

    document.querySelector(`#${forModal}`).classList.remove("active");
  })
);

// Payment implementation
document.querySelector(".open-confirm").addEventListener("click", (e) => {
  e.preventDefault();
  confirmOverlay.classList.remove("inactive");
});

document
  .querySelector(".signup form")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../../index.php", true);

    // Set the Content-Type header for form data
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Display success message
          document.getElementById("message").innerText =
            "User successfully registered";
        } else {
          // Display error message
          document.getElementById("message").innerText = "registration failed.";
        }
      }
    };

    // Get form data
    var formData = new FormData(document.querySelector(".signup > form"));

    // Send the form data
    xhr.send(formData);
  });
