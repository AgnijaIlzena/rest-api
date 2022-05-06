/**
 * fetch all movies
 */

fetch('http://api.test/rest-api/movies.php')
    .then(response => response.json())
    .then(movies=> {

        let tbody = document.querySelector('tbody');
        
        movies.forEach(movie => {
            console.log(movie);
           let tr = `  <tr>
           <th scope="row">${movie.id}</th>
           <td>
               <img class="w-25 rounded" src="${movie.image}" alt="">
           </td>
           <td>${movie.title}</td>
           <td>${movie.date}</td>
           <td>
               <a href="details.php?id=${movie.id}">Détails</a>
           </td>
       </tr>
           `;
           tbody.innerHTML += tr;
        });
    })
    .catch(error => alert(error));

    const form = document.querySelector('form');
    form.addEventListener('submit', (event)=> {
        event.preventDefault();

        const datas = {
            "title": document.querySelector('#title').ariaValueText,
            "description": document.querySelector('#description').ariaValueText,
            "date": document.querySelector('#date').ariaValueText,
            "time": document.querySelector('#time').ariaValueText,
            "director": document.querySelector('#director').ariaValueText,
            "image": document.querySelector('#image').ariaValueText,
            "trailer": document.querySelector('#trailer').ariaValueText
        }

        fetch('http://api.test/rest-api/add.php', {
            method: "POST", 
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(datas)
        })
            .then(response => response.json())
            .then(movie => {
                console.log(movie);

            })
            .catch(error => alert(error));

    })



