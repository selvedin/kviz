function errorNotification(message) {
  Swal.fire({
    icon: "error",
    title: "Greška",
    text: message,
    customClass: {
      confirmButton: "btn btn-primary",
    },
    buttonsStyling: false,
  });
}

const successNotification = () => {
  Swal.fire({
    position: "top-end",
    icon: "success",
    title: SUCCESS_TEXT,
    showConfirmButton: false,
    timer: 1500,
  });
};
