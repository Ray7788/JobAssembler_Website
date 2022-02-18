<?php

class Job
{
    public int $id;
    public string $title;
    public string $description;

    public static function create_job(string $title, string $description, string $location, int $company_id) {
        $pdo = Database::connect();
        $query = "INSERT INTO JobPostings (Title, Details, CompanyID) VALUES (:title, :description, 1)";
        $statement = $pdo->prepare($query);
        return $statement->execute([
            "title" => $title,
            "description" => $description
        ]);
    }
}