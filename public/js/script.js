let logo = document.querySelector("#jesmond_logo");
let sliding_img = document.querySelector("#sliding_image");

let images = ["roster1.jpeg", "roster2.jpeg", "roster3.jpeg", "roster5.jpeg"];
let index = 0;

function slider(asset) {
  index == images.length - 1 ? (index = 0) : index++;
  sliding_img.src = `${asset}/${images[index]}`;
}

// let rotation = 0;
// window.onload = function () {
//   setInterval(() => {
//     rotation += 90;
//     logo.style.transform = `rotate(${rotation}deg)`;

//     slider();
//   }, 5000);
// };

function reverseImg(asset) {
  let roster_index = sliding_img.src.indexOf("roster");
  if (roster_index !== -1) {
    let img_name = sliding_img.src.substring(roster_index);
    if (img_name === images[0]) {
      sliding_img.src = `${asset}/${images[0]}`;
    } else {
      let img_index = images.indexOf(img_name);
      sliding_img.src = `${asset}/${images[img_index - 1]}`;
      logo.style.transform = `rotate(${rotation - 90}deg)`;
      index--;
    }
  }
}

function forwardImg(asset) {
  let roster_index = sliding_img.src.indexOf("roster");
  if (roster_index !== -1) {
    let img_name = sliding_img.src.substring(roster_index);
    if (img_name === images[images.length - 1]) {
      sliding_img.src = `${asset}/${images[0]}`;
      index = 0;
    } else {
      let img_index = images.indexOf(img_name);
      sliding_img.src = `${asset}/${images[img_index + 1]}`;
      index++;
    }
    logo.style.transform = `rotate(${rotation + 90}deg)`;
  }
}
