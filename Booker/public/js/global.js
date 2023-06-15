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
let popupBtns = [closeNew, bookNew];
let newPopup = [overlay, newBooking];

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

// Transaction state implementation
let statusEl = document
  .querySelectorAll(".activities #status")
  .forEach((status) => {
    let editStatus = status.textContent.toLowerCase();

    status.style.backgroundColor =
      editStatus === "successful"
        ? "#08a129cc"
        : editStatus === "pending"
        ? "#ffae00cc"
        : "#f83600cc";
  });

// Modal immplementation
document.querySelectorAll(".open-modal").forEach((openBtn) =>
  openBtn.addEventListener("click", () => {
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
