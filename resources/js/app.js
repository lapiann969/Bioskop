
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

    // ==========================================
// PEMERIKSAAN STATUS PEMBAYARAN OTOMATIS
// ==========================================

const statusUrl = checkoutElement.dataset.statusUrl;

if (!statusUrl) {
    console.error("URL pemeriksaan pembayaran tidak ditemukan.");
    return;
}

let checkingStatus = false;

async function checkPaymentStatus() {
    if (checkingStatus) return;

    checkingStatus = true;

    try {
        const response = await fetch(statusUrl, {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error("Gagal memeriksa status pembayaran.");
        }

        const data = await response.json();

        console.log("STATUS PEMBAYARAN:", data);

        if (
            data.booking_status === "Paid" &&
            data.payment_status === "Success"
        ) {
            paymentMessage.textContent =
                "Pembayaran berhasil! Mengarahkan ke tiket...";

            submitButton.disabled = true;

            clearInterval(paymentStatusInterval);

            window.location.href = data.ticket_url;
        }

    } catch (error) {
        console.error("GAGAL CEK STATUS:", error);

    } finally {
        checkingStatus = false;
    }
}

// Periksa langsung saat halaman dibuka.
checkPaymentStatus();

// Periksa kembali setiap 3 detik.
const paymentStatusInterval = setInterval(
    checkPaymentStatus,
    3000
);

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

        components.addEventListener("submission-begin", () => {
    console.log("PEMBAYARAN MULAI DIPROSES");

    submitButton.disabled = true;
    submitButton.textContent = "Memproses pembayaran...";
});

components.addEventListener("submission-end", () => {
    console.log("PROSES SUBMISSION SELESAI");

    submitButton.textContent = "Bayar Sekarang";
});

components.addEventListener("session-complete", () => {
    console.log("SESSION BERHASIL DISELESAIKAN");

    paymentMessage.textContent =
        "Pembayaran berhasil. Menunggu konfirmasi server.";
});

components.addEventListener(
    "session-expired-or-canceled",
    () => {
        console.log("SESSION KEDALUWARSA ATAU DIBATALKAN");

        paymentMessage.textContent =
            "Sesi pembayaran berakhir atau dibatalkan.";
    }
);

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
