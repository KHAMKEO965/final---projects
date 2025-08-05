import 'package:flutter/material.dart';
import 'crud_screen.dart'; // ← ตรวจสอบว่าไฟล์นี้มีอยู่ใน lib/

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter CRUD',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.indigo,
      ),
      home: const CrudScreen(),
    );
  }
}
