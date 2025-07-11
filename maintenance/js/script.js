 function closeModal(id) {
      document.getElementById(id).classList.add("hidden");
    }

    function openViewTicket(problem) {
      document.getElementById("viewTicketModal").classList.remove("hidden");
      document.getElementById("problemText").value = problem;
    }

    document.getElementById("openAddCustomer")?.addEventListener("click", () => {
      document.getElementById("addCustomerModal").classList.remove("hidden");
    });

    document.getElementById("openSearchCustomer")?.addEventListener("click", () => {
      document.getElementById("searchCustomerModal").classList.remove("hidden");
    });

    document.getElementById("openAddTicket")?.addEventListener("click", () => {
      document.getElementById("addTicketModal").classList.remove("hidden");
    });

    document.getElementById("openAddEmployee")?.addEventListener("click", () => {
  document.getElementById("addEmployeeModal").classList.remove("hidden");
});

    document.getElementById("openSearchTicket")?.addEventListener("click", () => {
      document.getElementById("searchTicketModal").classList.remove("hidden");
    });

    document.getElementById("searchInput")?.addEventListener("input", function () {
      const query = this.value.toLowerCase();
      const customers = ["Ali Ahmad", "Layla Khoury", "John Doe"];
      const filtered = customers.filter(name => name.toLowerCase().includes(query));
      document.getElementById("searchResults").innerHTML = filtered.map(n => `<li>${n}</li>`).join("");
    });

    document.getElementById("ticketSearchInput")?.addEventListener("input", function () {
      const query = this.value.toLowerCase();
      const tickets = ["Device overheating - John Doe", "Screen issue - Layla"];
      const filtered = tickets.filter(t => t.toLowerCase().includes(query));
      document.getElementById("ticketSearchResults").innerHTML = filtered.map(t => `<li>${t}</li>`).join("");
    });
      if (!document.referrer.includes("index.php") && performance.navigation.type === 2) {
    // If user pressed back to return to dashboard, reload fully
    location.reload(true);
  }


    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm");

    const lengthCond = document.getElementById("length");
    const upperCond = document.getElementById("uppercase");
    const lowerCond = document.getElementById("lowercase");
    const numberCond = document.getElementById("number");
    const specialCond = document.getElementById("special");
    const matchCond = document.getElementById("match");

    function validatePassword() {
      const password = passwordInput.value;
      const confirm = confirmInput.value;

      // Conditions
      const hasLength = password.length >= 8;
      const hasUpper  = /[A-Z]/.test(password);
      const hasLower  = /[a-z]/.test(password);
      const hasNumber = /\d/.test(password);
      const hasSpecial = /[^A-Za-z0-9]/.test(password);
      const isMatch = password === confirm && password !== "";

      // Set UI
      setCondition(lengthCond, hasLength);
      setCondition(upperCond, hasUpper);
      setCondition(lowerCond, hasLower);
      setCondition(numberCond, hasNumber);
      setCondition(specialCond, hasSpecial);
      setCondition(matchCond, isMatch);

      // Field color
      passwordInput.className = hasLength && hasUpper && hasLower && hasNumber && hasSpecial ? "valid" : "invalid";
      confirmInput.className = isMatch ? "valid" : "invalid";

      return hasLength && hasUpper && hasLower && hasNumber && hasSpecial && isMatch;
    }

    function setCondition(element, passed) {
      element.className = passed ? "valid" : "invalid";
    }

    passwordInput.addEventListener("input", validatePassword);
    confirmInput.addEventListener("input", validatePassword);

    document.getElementById("registerForm").addEventListener("submit", function (e) {
      if (!validatePassword()) {
        alert("Please meet all password conditions.");
        e.preventDefault();
      }
    });