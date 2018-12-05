<?php

class SiteResultsProvider {
    private $_conn;

    public function __construct(PDO $conn)
    {
        $this->_conn = $conn;
    }

    public function getNumResults($term) {
        $stmt = 'SELECT COUNT(*) as total 
                FROM sites 
                WHERE title LIKE :term 
                OR url LIKE :term2
                OR keywords LIKE :term3
                OR description LIKE :term4';

        $query = $this->_conn->prepare($stmt);

        $searchTerm = "%$term%";
        $query->bindParam(':term', $searchTerm);
        $query->bindParam(':term2', $searchTerm);
        $query->bindParam(':term3', $searchTerm);
        $query->bindParam(':term4', $searchTerm);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getResultsHTML($page_num, $page_size, $term) {
        $stmt = 'SELECT * 
                FROM sites 
                WHERE title LIKE :term 
                OR url LIKE :term2
                OR keywords LIKE :term3
                OR description LIKE :term4
                ORDER BY clicks DESC';

        $query = $this->_conn->prepare($stmt);

        $searchTerm = "%$term%";
        $query->bindParam(':term', $searchTerm);
        $query->bindParam(':term2', $searchTerm);
        $query->bindParam(':term3', $searchTerm);
        $query->bindParam(':term4', $searchTerm);

        $query->execute();

        $resultsHTML = "<div class='site-results'>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $url = $row['url'];
            $title = $row['title'];
            $description = $row['description'];

            $resultsHTML .= "<div class='result-container'>
                                <h3 class='title'>
                                    <a class='result' href='$url'>$title</a>
                                </h3>
                                <span class='url'>$url</span>
                                <span class='description'>$description</span>
                            </div>";
        }

        $resultsHTML .= '</div>';

        return $resultsHTML;
    }
}