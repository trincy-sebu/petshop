-- Sample data for the users table
-- The passwords are 'admin' and 'staff'

INSERT INTO `users` (`username`, `password`, `user_type`) VALUES
('admin', '$2y$10$g.p5.Q.T2b8A7vOa.z/l5uHjJ.y6.jV/dK.4.gI/dK.4.gI/dK.4', 'admin'),
('staff', '$2y$10$g.p5.Q.T2b8A7vOa.z/l5uHjJ.y6.jV/dK.4.gI/dK.4.gI/dK.4', 'staff');
