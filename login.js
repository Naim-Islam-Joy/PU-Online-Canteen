// Placeholder for login logic
document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault();
  const role = document.getElementById("role").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  if (!role) {
    alert("Please select a role.");
    return;
  }

  // You can replace this with actual login logic (API, Firebase, etc.)
  console.log(`Logging in as ${role} with email: ${email}`);
  alert(`Login attempted for ${role}`);
});
