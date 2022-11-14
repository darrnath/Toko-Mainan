const btn_category = document.querySelector(".btn-category");
const category_overlay = document.querySelector(".category-overlay");
const btn_login = document.querySelector(".btn-login");
const login_overlay = document.querySelector(".login-overlay");
const filter_trig = document.querySelector(".filter-wrapper");
const filter_overlay = document.querySelector(".filter-overlay");
const setting_trig = document.querySelector(".setting-wrapper");
const setting_overlay = document.querySelector(".setting-overlay");
const picture = document.querySelector(".picture-wrapper img");

const loadFile = (event) => {
  picture.src = URL.createObjectURL(event.target.files[0]);
};

btn_category.addEventListener("mouseover", () => {
  category_overlay.classList.add("open-category");
});
category_overlay.addEventListener("mouseover", () => {
  category_overlay.classList.add("open-category");
});
btn_category.addEventListener("mouseout", () => {
  category_overlay.classList.remove("open-category");
});
category_overlay.addEventListener("mouseout", () => {
  category_overlay.classList.remove("open-category");
});

if (filter_trig !== null) {
  filter_trig.addEventListener("mouseover", () => {
    filter_overlay.classList.add("open-filter");
  });
  filter_overlay.addEventListener("mouseover", () => {
    filter_overlay.classList.add("open-filter");
  });
  filter_trig.addEventListener("mouseout", () => {
    filter_overlay.classList.remove("open-filter");
  });
  filter_overlay.addEventListener("mouseout", () => {
    filter_overlay.classList.remove("open-filter");
  });
}

if (setting_trig !== null) {
  setting_trig.addEventListener("mouseover", () => {
    setting_overlay.classList.add("open-setting");
  });
  setting_overlay.addEventListener("mouseover", () => {
    setting_overlay.classList.add("open-setting");
  });
  setting_trig.addEventListener("mouseout", () => {
    setting_overlay.classList.remove("open-setting");
  });
  setting_overlay.addEventListener("mouseout", () => {
    setting_overlay.classList.remove("open-setting");
  });
}

if (btn_login !== null) {
  btn_login.addEventListener("click", () => {
    login_overlay.classList.toggle("open-login");
  });
}
