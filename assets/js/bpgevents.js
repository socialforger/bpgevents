/**
 * BPGE Events – Frontend JS
 * Handles participation toggle and basic UI interactions
 */

document.addEventListener("DOMContentLoaded", function () {

    /**
     * PARTICIPATION BUTTON
     * ---------------------------------------------------------
     */
    const buttons = document.querySelectorAll(".bpgevents-participation-btn");

    if (buttons.length > 0) {

        buttons.forEach(function (btn) {

            btn.addEventListener("click", function () {

                const eventId = this.getAttribute("data-event-id");

                if (!eventId) return;

                const formData = new FormData();
                formData.append("action", "bpgevents_toggle_participation");
                formData.append("event_id", eventId);

                fetch(bpgevents_ajax.ajax_url, {
                    method: "POST",
                    credentials: "same-origin",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {

                    if (!data || !data.success) {
                        alert(data?.data?.message || "Error");
                        return;
                    }

                    // Update button text
                    if (data.data.status === "added") {
                        btn.textContent = bpgevents_ajax.leave_label;
                    } else {
                        btn.textContent = bpgevents_ajax.join_label;
                    }

                    // Update participants count
                    const countEl = btn.parentNode.querySelector(".bpgevents-participants-count");
                    if (countEl) {
                        countEl.textContent = bpgevents_ajax.participants_label.replace("%d", data.data.count);
                    }
                })
                .catch(() => {
                    alert("Request failed.");
                });
            });
        });
    }

});
