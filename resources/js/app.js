
import { XenditComponents } from "xendit-components-web";

document.addEventListener("DOMContentLoaded", () => {
    const checkoutElement = document.querySelector(
        "[data-xendit-checkout]"
    );

    if (!checkoutElement) return;

    const componentsSdkKey = checkoutElement.dataset.sdkKey;

    const paymentContainer = document.getElementById(
        "xendit-payment-container"
    );

    const submitButton = document.getElementById(
        "xendit-submit-button"
    );

    const paymentMessage = document.getElementById(
        "xendit-payment-message"
    );

    if (!componentsSdkKey || !paymentContainer ||
        !submitButton || !paymentMessage) {
        console.error("Elemen checkout tidak lengkap.");
        return;
    }

    try {
        console.log("MEMBUAT INSTANCE XENDIT");

        const components = new XenditComponents({
            componentsSdkKey,
        });

        console.log("INSTANCE XENDIT BERHASIL DIBUAT");

        // Event kesiapan pembayaran.
        components.addEventListener("submission-ready", () => {
            console.log("PEMBAYARAN SIAP");

            submitButton.disabled = false;
            paymentMessage.textContent =
                "Silakan lanjutkan pembayaran.";
        });

        components.addEventListener("submission-not-ready", () => {
            console.log("PEMBAYARAN BELUM SIAP");

            submitButton.disabled = true;
        });

        // Tombol pembayaran.
        submitButton.disabled = true;

        submitButton.addEventListener("click", () => {
            try {
                console.log("TOMBOL BAYAR DIKLIK");

                submitButton.disabled = true;
                components.submit();

            } catch (error) {
                console.error("GAGAL SUBMIT:", error);

                submitButton.disabled = false;
                paymentMessage.textContent =
                    "Gagal memproses pembayaran.";
            }
        });

        // Buat Channel Picker.
        console.log("MEMBUAT CHANNEL PICKER");

        const channelPicker =
            components.createChannelPickerComponent();

        paymentContainer.replaceChildren(channelPicker);

        console.log("CHANNEL PICKER BERHASIL DIMUAT");

    } catch (error) {
        console.error("XENDIT ERROR:", error);

        paymentMessage.textContent =
            "Komponen pembayaran gagal dimuat.";
    }
});
