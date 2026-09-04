# Student Attendance

## 📝 Problem Description

A student's attendance record can be represented as a string where each character indicates whether the student was **absent**, **late**, or **present** on that day. The record contains only the following three characters:

- `'A'`: Absent
- `'L'`: Late
- `'P'`: Present

A student is eligible for an attendance award if they meet **both** of the following criteria:

1. The student was absent (`'A'`) for **strictly fewer than 2 days** in total.
2. The student was **never** late (`'L'`) for **3 or more consecutive days**.

Given an integer `n`, return the **number of possible attendance records** of length `n` that make a student eligible for the attendance award. Since the answer may be very large, return the result **modulo 10⁹ + 7**.

