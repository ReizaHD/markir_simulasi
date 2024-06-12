let cropper = "";
let cropper_predict = "";
let model;
let slotData;
const sendBtn = document.getElementById("send_data");
const previewImage = document.getElementById("preview-image");
const previewImageCrop = document.getElementById("preview-image-crop");
const test = document.getElementById("test");
const webcam = new Webcam(document.getElementById('wc'));
if(slotData != null)
  console.log(slotData);

async function init(){
  await webcam.setup("device-list");
  // model = await tf.loadLayersModel("tf_model/model.json");
  model = await tf.loadLayersModel('tf_model2/model.json');
  console.log(model.summary());


}

async function captureNow(){
  console.log("pressed");
  const dataURL = await webcam.captureWithoutTensorFlow();
  previewImage.src = dataURL;
  previewImageCrop.src = dataURL;
  if(cropper){
    cropper.destroy();
  }
  cropper = new Cropper(previewImage);
  if(cropper_predict){
    cropper_predict.destroy();
  }
  cropper_predict = new Cropper(previewImageCrop);

}
// - id (unique)
// - left
// - top
// - width
// - height
// - column
// - row

async function cropCapture(){
  if(cropper){
    let data = cropper.getCropBoxData();//JSON
    let form = document.getElementById("hidden_form");
    console.log(data);
    const column = document.getElementById("column_input").value;
    const row = document.getElementById("row_input").value;

    console.log(row);

    //Tambah Element input
    if(data!= null) {
      document.getElementById("leftInp").value = data["left"];
      document.getElementById("topInp").value = data["top"];
      document.getElementById("widthInp").value = data["width"];
      document.getElementById("heightInp").value = data["height"];
      document.getElementById("columnInp").value = column;
      document.getElementById("rowInp").value = row;

      sendBtn.setAttribute("type","submit");
      sendBtn.setAttribute("form","hidden_form");
    }

    //kirim variable data, row, column ke database

  }
}

function convertToTf(imgSrc){
  return tf.tidy(() => {
    // Reads the image as a Tensor from the webcam <video> element.
    const webcamImage = tf.browser.fromPixels(imgSrc);

    const reversedImage = webcamImage.reverse(1);

    // Crop the image so we're using the center square of the rectangular
    // webcam.
    const croppedImage = webcam.cropImage(reversedImage);

    // Expand the outer most dimension so we have a batch size of 1.
    const batchedImage = croppedImage.expandDims(0);

    // Normalize the image between -1 and 1. The image comes in between 0-255,
    // so we divide by 127 and subtract 1.
    return batchedImage.toFloat().div(tf.scalar(127)).sub(tf.scalar(1));
  });
}

function sendData(){
  console.log("Pressed Send Data")
}

function cropReset(){
  if(cropper){
    cropper.reset();
  }
}

async function predict(img){
  let processedImg = convertToTf(img);
  let resizedTensorFrame = tf.image.resizeBilinear(processedImg,[150,
    150]);

  let predictions = await model.predict(resizedTensorFrame);
  // const myJSON = JSON.stringify(predictions.argMax());
  const classId = (await predictions.data())[0];
  if(classId >= 10e-20){
    return 0;
  }else {
    return 1;
  }
}

function setCrop(){
  if(cropper_predict){
    cropper_predict.setCropBoxData({left:1,top:1,width:50,height:50});
    let lol = cropper_predict.getCropBoxData();
    console.log(lol);
  }
}

init();
