function toggleFields() {
  const role = document.getElementById('role').value;

  // Hide all role-specific fields
  document.querySelectorAll('.role-fields').forEach((el) => {
    el.style.display = 'none';
  });

  // Show fields based on selected role
  if (role === 'student') {
    document.getElementById('studentFields').style.display = 'block';
  } else if (role === 'faculty') {
    document.getElementById('facultyFields').style.display = 'block';
  } else if (role === 'admin') {
    document.getElementById('adminFields').style.display = 'block';
  }
}
